<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Paiement;

class PaiementConfirmeNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Paiement confirmé - Al-Amine')
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Votre paiement a été confirmé avec succès !')
            ->line('Montant payé : ' . number_format($this->paiement->montant, 0, ',', ' ') . ' FCFA')
            ->line('Méthode : ' . $this->paiement->methode_paiement)
            ->line('Numéro de transaction : ' . $this->paiement->numero_transaction)
            ->action('Voir mes paiements', route('patient.paiements.index'))
            ->line('Merci de votre confiance !');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'paiement_confirme',
            'paiement_id' => $this->paiement->id,
            'montant' => $this->paiement->montant,
            'methode' => $this->paiement->methode_paiement,
            'numero_transaction' => $this->paiement->numero_transaction,
            'message' => 'Votre paiement de ' . number_format($this->paiement->montant, 0, ',', ' ') . ' FCFA a été confirmé avec succès.'
        ];
    }
}