<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('type_seances', function (Blueprint $table) {
            // كنزيدو حقل الثمن، الديفولت هو 0
            $table->decimal('prix', 10, 2)->default(0)->after('nom');
        });
    }

    public function down(): void
    {
        Schema::table('type_seances', function (Blueprint $table) {
            $table->dropColumn('prix');
        });
    }
};