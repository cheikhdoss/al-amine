<?php

namespace App\Notifications;

use App\Models\DemandeRdv;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeRdvStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demandeRdv;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(DemandeRdv $demandeRdv, string $status)
    {
        $this->demandeRdv = $demandeRdv;
        $this->status = $status;
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
        $message = (new MailMessage)
                    ->subject($this->getSubject())
                    ->greeting('Bonjour ' . $this->demandeRdv->patient->user->prenom . ',');

        if ($this->status === 'validee') {
            $message->line('Bonne nouvelle! Votre demande de rendez-vous a été **acceptée**.')
                    ->line('**Praticien:** Dr. ' . $this->demandeRdv->praticien->user->nom_complet)
                    ->line('**Date souhaitée:** ' . $this->demandeRdv->date_souhaitee->locale('fr')->isoFormat('dddd D MMMM YYYY'))
                    ->line('**Heure:** ' . $this->demandeRdv->heure_souhaitee)
                    ->action('Voir mes rendez-vous', route('patient.mes-rdv'))
                    ->line('Nous vous rappelons d\'arriver 10 minutes avant l\'heure de votre rendez-vous.');
        } else {
            $message->line('Votre demande de rendez-vous a été **refusée**.')
                    ->line('**Praticien:** Dr. ' . $this->demandeRdv->praticien->user->nom_complet)
                    ->line('**Date demandée:** ' . $this->demandeRdv->date_souhaitee->locale('fr')->isoFormat('dddd D MMMM YYYY'))
                    ->action('Faire une nouvelle demande', route('patient.demander-rdv'))
                    ->line('N\'hésitez pas à proposer d\'autres créneaux horaires.');
        }

        return $message->line('Merci d\'utiliser AL-AMINE.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'demande_rdv_status',
            'demande_rdv_id' => $this->demandeRdv->id,
            'status' => $this->status,
            'praticien_nom' => $this->demandeRdv->praticien->user->nom_complet,
            'message' => $this->status === 'validee' 
                ? 'Votre demande de RDV avec Dr. ' . $this->demandeRdv->praticien->user->nom_complet . ' a été acceptée'
                : 'Votre demande de RDV avec Dr. ' . $this->demandeRdv->praticien->user->nom_complet . ' a été refusée',
        ];
    }

    private function getSubject(): string
    {
        return $this->status === 'validee' 
            ? 'Demande de rendez-vous acceptée - AL-AMINE'
            : 'Demande de rendez-vous refusée - AL-AMINE';
    }
}
