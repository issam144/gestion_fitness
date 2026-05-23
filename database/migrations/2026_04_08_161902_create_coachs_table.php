<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coachs', function (Blueprint $table) {
            $table->id();
            
            // الربط مع جدول users (الاسم، الايميل، والباسورد كاينين تما)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // معلومات خاصة بالكوتش فقط
            $table->string('specialite'); // مثلا: Crossfit, Yoga
            $table->string('telephone')->nullable();
            $table->string('image')->nullable(); 
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coachs');
    }
};