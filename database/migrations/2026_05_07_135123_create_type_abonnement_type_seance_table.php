<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_abonnement_type_seance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_abonnement_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_seance_id')->constrained('type_seances')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_abonnement_type_seance');
    }
};
