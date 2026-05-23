<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seance extends Model 
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'date_seance', 
        'heure_seance', 
        'coach_id', 
        'type_seance_id',
        'statut_coach', 
        'note_admin'
    ];

    /**
     * العلاقة مع الكوتش
     */
    public function coach(): BelongsTo
    { 
        return $this->belongsTo(Coach::class); 
    }

    /**
     * العلاقة مع نوع الحصة (الاسم الأصلي)
     */
    public function type_seance(): BelongsTo
    { 
        return $this->belongsTo(TypeSeance::class, 'type_seance_id'); 
    }

    /**
     * إصلاح الخطأ: إضافة علاقة كـ Alias 
     * هادي هي اللي غتحل ليك مشكل RelationNotFoundException ✅
     */
    public function typeSeance(): BelongsTo
    {
        return $this->type_seance();
    }

    /**
     * العلاقة مع الأعضاء المنخرطين في الحصة
     */
    public function members(): BelongsToMany
    {
        // تم ربطها بجدول seance_member والتحقق من الـ IDs
        return $this->belongsToMany(User::class, 'seance_member', 'seance_id', 'user_id')
                    ->withPivot('is_present')
                    ->withTimestamps();
    }

    /* --- دالة مساعدة للتحقق من الحالة (اختياري) --- */
    public function isCompleted()
    {
        return $this->statut_coach === 'present';
    }
}