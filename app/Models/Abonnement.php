<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_abonnement_id',
        'date_debut',
        'date_fin',
        'montant_paye',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeAbonnement()
    {
        return $this->belongsTo(TypeAbonnement::class);
    }

    public function typeSeances()
    {
        return $this->belongsToMany(TypeSeance::class, 'abonnement_type_seance', 'abonnement_id', 'type_seance_id');
    }
}