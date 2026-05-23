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
        Schema::create('seance_member', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('seance_id');
            // استعملنا user_id حيت المتدربين كاينين فجدول users
            $table->unsignedBigInteger('user_id'); 
            
            $table->boolean('is_present')->default(false);
            $table->timestamps();

            // الربط مع الحصص
            $table->foreign('seance_id')->references('id')->on('seances')->onDelete('cascade');
            
            // الربط مع المستخدمين (المتدربين)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seance_member');
    }
};