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
        // جرب تبدل coaches لـ coachs
        Schema::table('coachs', function (Blueprint $table) {
            if (!Schema::hasColumn('coachs', 'rating')) {
                $table->decimal('rating', 3, 2)->default(5.00);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coachs', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};