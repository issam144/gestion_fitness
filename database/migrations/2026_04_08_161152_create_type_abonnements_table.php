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
    Schema::create('type_abonnements', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->integer('duree_mois'); // <--- تأكد أن هاد السطر كاين بهاد السمية
        $table->decimal('prix', 8, 2);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_abonnements');
    }
};
