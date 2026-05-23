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
        Schema::table('users', function (Blueprint $table) {
            // كنزيدو الخانة والربط مباشرة بلا ما نحاولو نمسحو شي حاجة قديمة
            $table->foreignId('abonnement_id')
                  ->nullable()
                  ->after('role') // باش تجي منظمة مورا الـ role
                  ->constrained('type_abonnements') 
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // هادي ضرورية يلا بغيتي تدير rollback مستقبلاً
            $table->dropForeign(['abonnement_id']);
            $table->dropColumn('abonnement_id');
        });
    }
};