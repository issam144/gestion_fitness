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
    Schema::table('type_seances', function (Blueprint $table) {
        $table->string('icon')->nullable()->after('nom'); // زدنا هاد السطر
    });
}

public function down(): void
{
    Schema::table('type_seances', function (Blueprint $table) {
        $table->dropColumn('icon');
    });
}
};
