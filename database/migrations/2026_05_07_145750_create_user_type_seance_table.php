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
    Schema::create('user_type_seance', function (Blueprint $table) {
        $table->id();
        // كيربط مع جدول الـ Users
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // كيربط مع جدول الرياضات (تأكد أن اسم الجدول هو type_seances)
        $table->foreignId('type_seance_id')->constrained('type_seances')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_type_seance');
    }
};
