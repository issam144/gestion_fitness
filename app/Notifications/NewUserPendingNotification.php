<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserPendingNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Homologation Requise',
            'message' => "Un nouveau profil (" . $this->user->name . ") attend votre validation système.",
            'user_id' => $this->user->id,
            'type' => 'registration_request'
        ];
    }
}