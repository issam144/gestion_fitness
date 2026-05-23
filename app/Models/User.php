<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'role', 
        'is_approved', 
        'abonnement_id', 
        'expired_at',
        'must_change_password',
        'image',
        'coach_id',
    ];

    /**
     * الحقول المخفية عند التحويل لـ JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات (Casting)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'expired_at' => 'datetime', 
            'is_approved' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    /* -----------------------------------------------------------
       --- الدوال المساعدة للتحقق من الأدوار (Role Helpers) ---
       ----------------------------------------------------------- */

    public function isAdmin() { return $this->role === 'admin'; }
    public function isCoach() { return $this->role === 'coach'; }
    public function isClient() { return $this->role === 'client'; }

    public function isActive()
    {
        return $this->is_approved && $this->expired_at && $this->expired_at->isFuture();
    }

    /* -----------------------------------------------------------
       --- العلاقات (Relationships) ---
       ----------------------------------------------------------- */

    /**
     * [FIXED] العلاقة مع الرياضات/التخصصات (Disciplines) ✅
     * هادي هي لي كانت ناقصة وكتدير Error فاش كتحاول تخلص (Abonnement)
     */
    public function typeSeances()
    {
        return $this->belongsToMany(TypeSeance::class, 'user_type_seance', 'user_id', 'type_seance_id');
    }

    /**
     * العلاقة مع المدربين (ManyToMany)
     */
    public function coachs()
    {
        return $this->belongsToMany(Coach::class, 'coach_member', 'user_id', 'coach_id')
                    ->withTimestamps();
    }

    /**
     * العلاقة مع حصص الحضور
     */
    public function seances()
    {
        return $this->belongsToMany(Seance::class, 'seance_member', 'user_id', 'seance_id')
                    ->withPivot('is_present')
                    ->withTimestamps();
    }

    /**
     * العلاقة مع ملف المدرب (في حالة كان المستخدم مدرباً)
     */
    public function coach()
    {
        return $this->hasOne(Coach::class);
    }

    /**
     * العلاقة مع جدول الاشتراكات (العمليات المالية)
     */
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    /**
     * العلاقة مع نوع الاشتراك المختار (Pack)
     */
    public function typeAbonnement()
    {
        return $this->belongsTo(TypeAbonnement::class, 'abonnement_id');
    }
}