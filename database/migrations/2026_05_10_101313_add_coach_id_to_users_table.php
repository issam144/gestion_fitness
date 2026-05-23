<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // كنزيدو الحقل اللي كيربط المستخدم بالكوتش
            // nullable() حيت الكليان كيكون فالبدية مازال ماختارش كوتش
            // تأكد أن constrained فيها 'coachs' (بالـ s) حيت هكا سميتي الجدول قبيلة
            $table->foreignId('coach_id')->nullable()->after('role')->constrained('coachs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['coach_id']);
            $table->dropColumn('coach_id');
        });
    }
};