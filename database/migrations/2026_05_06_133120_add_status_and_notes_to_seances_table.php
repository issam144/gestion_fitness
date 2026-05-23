<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('seances', function (Blueprint $table) {
        // pending = في الانتظار، present = الكوتش حضر، absent = الكوتش غايب
        $table->string('statut_coach')->default('pending'); 
        $table->text('note_admin')->nullable(); // ملاحظة من الكوتش للإدمين
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            //
        });
    }
};
