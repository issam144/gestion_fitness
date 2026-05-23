<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Seance; // ضروري تزيد هادي باش السيستيم يعرف موديل السانس

class SessionCanceledNotification extends Notification
{
    use Queueable;

    protected $seance;

    /**
     * دوزنا السانس باش نخدمو ببياناتها (الوقت، السمية...) ✅
     */
    public function __construct(Seance $seance)
    {
        $this->seance = $seance;
    }

    /**
     * بدلناها لـ database باش تطلع فـ Dashboard عند الكليان ✅
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * هاد البيانات هي لي غاتطلع للكليان فـ "الجرس" ✅
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'session_canceled',
            'message' => "Désolé, votre séance de " . ($this->seance->typeSeance->nom ?? 'SÉANCE') . " prévue à " . $this->seance->heure_seance . " a été annulée.",
            'seance_id' => $this->seance->id,
        ];
    }
}