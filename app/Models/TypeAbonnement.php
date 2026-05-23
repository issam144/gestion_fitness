<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeAbonnement extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
        protected $fillable = ['nom', 'duree_mois', 'prix', 'description'];
    /**
     * Relation : Un type d'abonnement peut avoir plusieurs abonnements.
     * (Un type_abonnement "a plusieurs" abonnements)
     */
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }
    public function typeSeances()
{
    // هادي هي العلاقة لي كانت ناقصة ✅
    // تأكد أن اسم الجدول الوسيط هو 'type_abonnement_type_seance'
    return $this->belongsToMany(TypeSeance::class, 'type_abonnement_type_seance');
}
}