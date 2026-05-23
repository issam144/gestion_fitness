<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachRating extends Model
{
    use HasFactory;

    // زيد هاد السطر هنا باش تسمح بزيادة هاد الحقول
    protected $fillable = ['user_id', 'coach_id', 'stars'];
}