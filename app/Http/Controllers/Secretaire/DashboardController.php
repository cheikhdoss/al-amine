<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\DemandeRdv;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Praticien;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $secretaire = auth()->user()->secretaire;

        // Statistiques du dashboard
        $stats = [
            'demandes_attente' => DemandeRdv::where('statut', 'EN_ATTENTE')->count(),
            'rdv_aujourdhui' => RendezVous::whereDate('date_heure_rdv', today())->count(),
            'factures_impayees' => Facture::where('statut', 'EMISE')->count(),
        ];

        // Demandes en attente (5 premières)
        $demandesRecentes = DemandeRdv::with(['patient.user', 'praticien.user', 'specialite'])
            ->where('statut', 'EN_ATTENTE')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // RDV du jour
        $rdvAujourdhui = RendezVous::with(['patient.user', 'praticien.user'])
            ->whereDate('date_heure_rdv', today())
            ->orderBy('date_heure_rdv')
            ->get();

        // Factures récentes
        $facturesRecentes = Facture::with(['consultation.patient.user', 'consultation.praticien.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('secretaire.dashboard', compact('stats', 'demandesRecentes', 'rdvAujourdhui', 'facturesRecentes'));
    }

    public function agendas()
    {
        $praticiens = Praticien::with(['user', 'service', 'specialites'])
            ->get();

        return view('secretaire.agendas', compact('praticiens'));
    }

    public function agendaPraticien(Praticien $praticien)
    {
        $rendezVous = RendezVous::with(['patient.user', 'consultation'])
            ->where('praticien_id', $praticien->id)
            ->where('date_heure_rdv', '>=', now()->startOfWeek())
            ->where('date_heure_rdv', '<=', now()->endOfWeek()->addWeeks(2))
            ->orderBy('date_heure_rdv')
            ->get();

        return view('secretaire.agenda-praticien', compact('praticien', 'rendezVous'));
    }

    public function facturation()
    {
        $consultationsSansFacture = Consultation::with(['patient.user', 'praticien.user'])
            ->whereDoesntHave('facture')
            ->where('est_validee', true)
            ->orderBy('date_consultation', 'desc')
            ->get();

        $factures = Facture::with(['consultation.patient.user', 'consultation.praticien.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('secretaire.facturation', compact('consultationsSansFacture', 'factures'));
    }

    public function genererFacture(Consultation $consultation)
    {
        // Vérifier si une facture existe déjà
        if ($consultation->facture) {
            return redirect()->route('secretaire.facturation')
                ->with('error', 'Une facture existe déjà pour cette consultation.');
        }

        return view('secretaire.generer-facture', compact('consultation'));
    }

    public function storeFacture(Request $request, Consultation $consultation)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);

        $facture = Facture::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $consultation->patient_id,
            'praticien_id' => $consultation->praticien_id,
            'numero_facture' => 'FAC-' . now()->format('Ymd') . '-' . str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT),
            'date_facture' => now(),
            'montant' => $request->montant,
            'details' => $request->details,
            'statut' => 'EMISE',
            'emise_par' => auth()->user()->secretaire->id,
        ]);

        return redirect()->route('secretaire.facturation')
            ->with('success', 'Facture générée avec succès.');
    }

    public function encaissements()
    {
        $stats = [
            'total_jour' => Paiement::whereDate('created_at', today())->sum('montant'),
            'total_mois' => Paiement::whereMonth('created_at', now()->month)->sum('montant'),
            'nb_paiements_jour' => Paiement::whereDate('created_at', today())->count(),
            'factures_impayees' => Facture::where('statut', 'EMISE')->count(),
        ];

        $paiements = Paiement::with(['facture.consultation.patient.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('secretaire.encaissements', compact('stats', 'paiements'));
    }
}
