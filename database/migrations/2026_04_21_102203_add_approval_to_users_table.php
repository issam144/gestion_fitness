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
            // 1. كنزيدو خانة واش مقبول ولا لا، وكيبدا بـ false (0)
            $table->boolean('is_approved')->default(false)->after('password');

            // 2. كنردو الـ role يقبل يكون خاوي (nullable) حيت مابقاش كيتعمر فالتسجيل
            // ملاحظة: إلا كان الـ role ديجا كاين، هاد السطر غادي يردو nullable
            $table->string('role')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // باش إلا بغينا نرجعو باللور يمسح هادشي
            $table->dropColumn('is_approved');
            // نرجعو الـ role كيف كان (اختياري على حسب شنو كان عندك)
            $table->string('role')->nullable(false)->change();
        });
    }
};