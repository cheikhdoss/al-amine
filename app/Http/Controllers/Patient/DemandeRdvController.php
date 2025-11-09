<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DemandeRdv;
use App\Models\Paiement;
use App\Models\Specialite;
use App\Models\Praticien;
use App\Services\PaydunyaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DemandeRdvController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        $demandes = DemandeRdv::with(['praticien.user', 'specialite', 'traitePar.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('patient.mes-demandes', compact('demandes'));
    }

    public function create()
    {
        $specialites = Specialite::all();

        return view('patient.demander-rdv', compact('specialites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialite_id' => 'required|exists:specialites,id',
            'praticien_id' => 'required|exists:praticiens,id',
            'date_heure_souhaitee' => 'required|date|after:now',
            'motif' => 'required|string|max:500',
            'mode_paiement' => 'required|in:EN_LIGNE,SUR_PLACE',
            'methode_paiement' => 'required_if:mode_paiement,EN_LIGNE|nullable|in:CARTE_BANCAIRE,WAVE,ORANGE_MONEY,SUR_PLACE',
        ]);

        $patient = auth()->user()->patient;
        $praticien = Praticien::findOrFail($request->praticien_id);

        DB::beginTransaction();
        
        try {
            // Déterminer le statut initial selon le mode de paiement
            $statutInitial = $request->mode_paiement === 'EN_LIGNE' ? 'EN_ATTENTE_PAIEMENT' : 'EN_ATTENTE';

            // Créer la demande de RDV
            $demande = DemandeRdv::create([
                'patient_id' => $patient->id,
                'praticien_id' => $request->praticien_id,
                'specialite_id' => $request->specialite_id,
                'date_heure_souhaitee' => $request->date_heure_souhaitee,
                'motif' => $request->motif,
                'statut' => $statutInitial,
                'mode_paiement' => $request->mode_paiement,
                'methode_paiement_choisie' => $request->methode_paiement,
                'paiement_effectue' => false,
            ]);

            // Si paiement sur place, on termine ici (statut déjà EN_ATTENTE)
            if ($request->mode_paiement === 'SUR_PLACE') {
                DB::commit();
                
                return redirect()->route('patient.mes-demandes')
                    ->with('success', 'Votre demande de rendez-vous a été envoyée avec succès. Vous pourrez payer lors de votre visite.');
            }

            // Paiement en ligne avec PayDunya
            // Calculer le montant (15 000 FCFA comme montant estimé)
            $montant = 15000; // Vous pouvez aussi utiliser $praticien->tarif_consultation si disponible

            // Mapper CARTE_BANCAIRE vers CARTE pour correspondre à l'enum DB
            $methodePaiement = $request->methode_paiement === 'CARTE_BANCAIRE' ? 'CARTE' : $request->methode_paiement;

            // Créer l'enregistrement de paiement
            $paiement = Paiement::create([
                'patient_id' => $patient->id,
                'montant' => $montant,
                'methode_paiement' => $methodePaiement,
                'statut' => 'EN_ATTENTE',
                'demande_rdv_id' => $demande->id,
                'reference' => 'RDV-' . Str::upper(Str::random(10)),
            ]);

            // Stocker le montant avancé pour suivi
            $demande->update([
                'montant_avance' => $montant,
            ]);

            // Appeler le service PayDunya pour créer la facture
            $paydunyaService = app(PaydunyaService::class);
            $result = $paydunyaService->createInvoice($demande, $paiement, $methodePaiement, $montant);

            // Vérifier que l'URL de paiement a été générée
            if (empty($result['invoice_url'])) {
                throw new \Exception('URL de paiement PayDunya non reçue');
            }

            // Enregistrer le token PayDunya
            $demande->update([
                'paydunya_token' => $result['token'] ?? null,
            ]);

            // Mettre à jour le paiement avec le token
            $paiement->update([
                'numero_transaction' => $result['token'] ?? null,
            ]);

            DB::commit();

            Log::info('PayDunya - Redirection vers page de paiement', [
                'demande_id' => $demande->id,
                'paiement_id' => $paiement->id,
                'token' => $result['token'] ?? null,
                'url' => $result['invoice_url'],
            ]);

            // Rediriger vers la page de paiement PayDunya
            return redirect($result['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la création du paiement PayDunya', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du paiement. Veuillez réessayer ou choisir de payer sur place.');
        }
    }

    public function getPraticiensBySpecialite($specialiteId)
    {
        $praticiens = Praticien::with('user')
            ->whereHas('specialites', function($query) use ($specialiteId) {
                $query->where('specialite_id', $specialiteId);
            })
            ->get()
            ->map(function($praticien) {
                return [
                    'id' => $praticien->id,
                    'nom' => $praticien->user->nom_complet,
                    'tarif' => $praticien->tarif_format,
                    'experience' => $praticien->annees_experience . ' ans',
                ];
            });

        return response()->json($praticiens);
    }
}

