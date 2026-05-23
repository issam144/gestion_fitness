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
    Schema::create('abonnements', function (Blueprint $table) {
        $table->id();
        
        // 1. رابط مع المنخرط (ضروري باش نعرفو شكون خلص)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // 2. رابط مع نوع الاشتراك (شهري، سنوي...)
        $table->foreignId('type_abonnement_id')->constrained('type_abonnements')->onDelete('cascade');
        
        // 3. المعلومات المالية (سميتو montant_paye باش يمشي مع الـ Controller)
        $table->decimal('montant_paye', 8, 2); 
        
        // 4. التواريخ (باش نعرفو واش Expire ولا لا)
        $table->date('date_debut');
        $table->date('date_fin'); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
