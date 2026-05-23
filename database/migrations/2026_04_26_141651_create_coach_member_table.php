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
    Schema::create('coach_member', function (Blueprint $table) {
        $table->id();
        // هادا هو الـ ID ديال المنخرط (المستعمل)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // هادا هو الـ ID ديال الكوتش
        $table->foreignId('coach_id')->constrained('coachs')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_member');
    }
};
