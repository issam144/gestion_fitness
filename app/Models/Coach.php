<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coach extends Model
{
    use HasFactory;

    /**
     * اسم الجدول في قاعدة البيانات
     */
    protected $table = 'coachs'; 

    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'type_seance_id', 
        'specialite',
        'telephone',
        'image',
        'statut',
        'rating',
        'experience', 
        'bio'
    ];

    /**
     * العلاقة مع حساب المستخدم الأساسي (الاسم، الإيميل، الرول...)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع نوع الرياضة أو التخصص (Category)
     */
    public function typeSeance(): BelongsTo
    {
        return $this->belongsTo(TypeSeance::class, 'type_seance_id');
    }

    /**
     * العلاقة مع الحصص التدريبية المسندة لهذا الكوتش
     * (مهمة جداً لحساب ساعات العمل والتقارير المالية)
     */
    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }

    /**
     * العلاقة مع المنخرطين (الأعضاء) المسجلين مباشرة مع هذا الكوتش
     * تستخدم في واجهة "Mes Membres" عند الكوتش
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coach_member', 'coach_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * دالة مساعدة لحساب عدد الحصص المكتملة (اختياري)
     */
    public function completedSessionsCount()
    {
        return $this->seances()->where('statut_coach', 'present')->count();
    }
}