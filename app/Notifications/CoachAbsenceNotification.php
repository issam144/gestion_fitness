<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Seance; // ضروري تزيد هادي

class CoachAbsenceNotification extends Notification
{
    use Queueable;

    protected $seance;

    /**
     * دوزنا السانس هنا باش نقدرو نخدمو بيها ✅
     */
    public function __construct(Seance $seance)
    {
        $this->seance = $seance;
    }

    /**
     * زدنا database هنا باش تطلع فـ السيستيم ✅
     */
    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    /**
     * هاد البيانات هي لي كتقراها فـ ملف app.blade.php ✅
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'absence_signal',
            'message' => "Le coach " . auth()->user()->name . " a signalé son absence pour la séance de " . ($this->seance->typeSeance->nom ?? 'SÉANCE'),
            'seance_id' => $this->seance->id,
        ];
    }
}