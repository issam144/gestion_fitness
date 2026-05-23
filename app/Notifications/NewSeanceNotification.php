<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSeanceNotification extends Notification
{
    use Queueable;

    protected $seance;

    /**
     * Create a new notification instance.
     * On passe l'objet Seance pour accéder à ses détails
     */
    public function __construct($seance)
    {
        $this->seance = $seance;
    }

    /**
     * Get the notification's delivery channels.
     * On utilise la base de données pour stocker les notifications
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Ces données seront affichées dans l'interface (Navbar)
     */
    public function toArray(object $notifiable): array
    {
        // Récupérer le nom du type de séance (ex: Yoga, Boxe...)
        $typeName = $this->seance->type_seance->nom ?? 'Séance';
        $date = $this->seance->date_seance;
        $heure = $this->seance->heure_seance;

        return [
            'title' => 'Nouvelle Séance Programmée',
            'seance_id' => $this->seance->id,
            'coach_name' => $this->seance->coach->user->name ?? 'Coach',
            'message' => "Une nouvelle séance de {$typeName} est prévue le {$date} à {$heure}.",
        ];
    }
}