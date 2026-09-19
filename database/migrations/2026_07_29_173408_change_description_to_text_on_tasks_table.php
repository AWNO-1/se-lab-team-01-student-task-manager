<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * توسيع حقل الوصف ليتوافق مع الحد الأقصى المسموح به في التحقق.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * إعادة حقل الوصف إلى نوعه السابق.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });
    }
};
