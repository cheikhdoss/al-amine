<?php

namespace App\Notifications;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FactureImpayeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $facture;

    /**
     * Create a new notification instance.
     */
    public function __construct(Facture $facture)
    {
        $this->facture = $facture;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Facture impayée - AL-AMINE')
                    ->greeting('Bonjour ' . $this->facture->patient->user->prenom . ',')
                    ->line('Nous vous rappelons qu\'une facture reste impayée.')
                    ->line('**Numéro de facture:** #' . $this->facture->numero_facture)
                    ->line('**Date:** ' . $this->facture->date_facture->format('d/m/Y'))
                    ->line('**Montant total:** ' . number_format($this->facture->montant_total, 0, ',', ' ') . ' FCFA')
                    ->line('**Montant restant:** ' . number_format($this->facture->montant_restant, 0, ',', ' ') . ' FCFA')
                    ->action('Payer maintenant', route('patient.paiement', $this->facture))
                    ->line('Merci de régulariser votre situation dans les meilleurs délais.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'facture_impayee',
            'facture_id' => $this->facture->id,
            'numero_facture' => $this->facture->numero_facture,
            'montant_restant' => $this->facture->montant_restant,
            'message' => 'Facture #' . $this->facture->numero_facture . ' impayée - Montant: ' . number_format($this->facture->montant_restant, 0, ',', ' ') . ' FCFA',
        ];
    }
}
