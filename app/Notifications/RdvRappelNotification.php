<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RdvRappelNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $rendezVous;

    /**
     * Create a new notification instance.
     */
    public function __construct(RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;
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
                    ->subject('Rappel de rendez-vous - AL-AMINE')
                    ->greeting('Bonjour ' . $this->rendezVous->patient->user->prenom . ',')
                    ->line('Ceci est un rappel pour votre rendez-vous médical.')
                    ->line('**Praticien:** Dr. ' . $this->rendezVous->praticien->user->nom_complet)
                    ->line('**Date:** ' . $this->rendezVous->date_heure_rdv->locale('fr')->isoFormat('dddd D MMMM YYYY'))
                    ->line('**Heure:** ' . $this->rendezVous->date_heure_rdv->format('H:i'))
                    ->line('**Durée:** ' . $this->rendezVous->duree_minutes . ' minutes')
                    ->action('Voir mon rendez-vous', route('patient.rendezvous.show', $this->rendezVous))
                    ->line('Merci d\'utiliser AL-AMINE pour vos soins de santé.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'rdv_rappel',
            'rendez_vous_id' => $this->rendezVous->id,
            'praticien_nom' => $this->rendezVous->praticien->user->nom_complet,
            'date_heure' => $this->rendezVous->date_heure_rdv->format('Y-m-d H:i'),
            'message' => 'Rappel: Rendez-vous demain avec Dr. ' . $this->rendezVous->praticien->user->nom_complet . ' à ' . $this->rendezVous->date_heure_rdv->format('H:i'),
        ];
    }
}
