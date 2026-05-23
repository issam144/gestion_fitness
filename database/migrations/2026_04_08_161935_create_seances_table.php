<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->date('date_seance'); 
            $table->time('heure_seance');
            
            // ربط مع جدول المدربين (تأكد أن اسم الجدول coachs)
            $table->foreignId('coach_id')->constrained('coachs')->onDelete('cascade');
            
            // ربط مع أنواع الحصص
            $table->foreignId('type_seance_id')->constrained('type_seances')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('seances');
    }
};