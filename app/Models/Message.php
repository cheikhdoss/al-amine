<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'expediteur_id',
        'destinataire_id',
        'rendez_vous_id',
        'type',
        'contenu',
        'fichier',
        'lu',
        'lu_at',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'lu_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relations
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class, 'rendez_vous_id');
    }

    // Scopes
    public function scopeConversation($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('expediteur_id', $userId1)->where('destinataire_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('expediteur_id', $userId2)->where('destinataire_id', $userId1);
        });
    }

    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }

    public function scopeForConversation($query, int $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }
}
