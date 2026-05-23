<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeAbonnement;
use App\Models\TypeSeance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des types d'abonnements
        TypeAbonnement::create(['nom' => 'Mensuel']);
        TypeAbonnement::create(['nom' => 'Trimestriel']);
        TypeAbonnement::create(['nom' => 'Annuel']);

        // Créer des types de séances
        TypeSeance::create(['nom' => 'Cardio']);
        TypeSeance::create(['nom' => 'Musculation']);
        TypeSeance::create(['nom' => 'Yoga']);

        // Créer un utilisateur Admin pour tester
        User::create([
            'name' => 'Admin Fitness',
            'email' => 'admin@fitness.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        
        // Créer un utilisateur Coach pour tester
        User::create([
            'name' => 'Coach Ahmed',
            'email' => 'ahmed@fitness.com',
            'password' => Hash::make('password'),
            'role' => 'coach'
        ]);
    }
}