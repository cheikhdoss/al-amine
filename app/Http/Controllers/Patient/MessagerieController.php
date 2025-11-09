<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Praticien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessagerieController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Récupérer toutes les conversations (praticiens avec qui le patient a échangé)
        $conversations = Message::where('expediteur_id', $userId)
            ->orWhere('destinataire_id', $userId)
            ->with(['expediteur', 'destinataire'])
            ->get()
            ->map(function ($message) use ($userId) {
                return $message->expediteur_id === $userId 
                    ? $message->destinataire 
                    : $message->expediteur;
            })
            ->unique('id')
            ->values();

        // Compter les messages non lus par conversation
        foreach ($conversations as $contact) {
            $contact->unread_count = Message::where('expediteur_id', $contact->id)
                ->where('destinataire_id', $userId)
                ->where('lu', false)
                ->count();
        }

        return view('patient.messagerie.index', compact('conversations'));
    }

    public function show(User $praticien)
    {
        $userId = auth()->id();
        
        // Récupérer tous les messages de la conversation
        $messages = Message::conversation($userId, $praticien->id)
            ->with(['expediteur', 'destinataire'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages reçus comme lus
        Message::where('expediteur_id', $praticien->id)
            ->where('destinataire_id', $userId)
            ->where('lu', false)
            ->update(['lu' => true, 'lu_at' => now()]);

        return view('patient.messagerie.conversation', compact('praticien', 'messages'));
    }

    public function store(Request $request, User $praticien)
    {
        $validated = $request->validate([
            'contenu' => 'required|string',
            'fichier' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $fichierPath = null;
        if ($request->hasFile('fichier')) {
            $fichierPath = $request->file('fichier')->store('messages', 'public');
        }

        Message::create([
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $praticien->id,
            'contenu' => $validated['contenu'],
            'fichier' => $fichierPath,
        ]);

        return back()->with('success', 'Message envoyé avec succès');
    }

    public function nouveauMessage()
    {
        // Récupérer les praticiens avec qui le patient a eu des RDV
        $patient = auth()->user()->patient;
        $praticiens = Praticien::whereHas('rendezVous', function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })->with(['user', 'specialites'])->get();

        return view('patient.messagerie.nouveau', compact('praticiens'));
    }

    public function getMessages(User $praticien)
    {
        $userId = auth()->id();
        
        $messages = Message::conversation($userId, $praticien->id)
            ->with(['expediteur'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        // Marquer comme lus
        Message::where('expediteur_id', $praticien->id)
            ->where('destinataire_id', $userId)
            ->where('lu', false)
            ->update(['lu' => true, 'lu_at' => now()]);

        return response()->json($messages);
    }
}
