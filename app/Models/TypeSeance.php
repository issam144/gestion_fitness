<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeSeance extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها
     * زدت ليك 'prix' حيت ضروري باش السيستيم يحسب المجموع
     */
    protected $fillable = [
        'nom', 
        'icon',
        'prix' // ضروري باش تعرف كل رياضة شحال كتدير
    ];

    /**
     * علاقة مع الاشتراكات (Many-to-Many) ✅
     * هادي هي لي غاتخلينا نعرفو هاد الرياضة شكون مسجل فيها
     */
    public function abonnements()
    {
        return $this->belongsToMany(Abonnement::class, 'abonnement_type_seance', 'type_seance_id', 'abonnement_id');
    }

    /**
     * علاقة مع الحصص: النوع الواحد له عدة حصص
     */
    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    /**
     * علاقة مع المدربين
     */
    public function coachs()
    {
        return $this->hasMany(Coach::class, 'type_seance_id');
    }
}