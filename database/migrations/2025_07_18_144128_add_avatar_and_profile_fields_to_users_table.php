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
            $table->string('avatar')->after('last_login_at')->nullable()->comment('الصورة الشخصية');
            $table->boolean('profile_completed')->after('avatar')->default(false)->comment('هل تم إكمال الملف الشخصي');
            $table->string('language')->after('profile_completed')->default('ar')->comment('اللغة المفضلة');
            $table->string('timezone')->after('language')->default('Asia/Riyadh')->comment('المنطقة الزمنية');
            $table->boolean('email_notifications')->after('timezone')->default(true)->comment('إشعارات البريد الإلكتروني');
            $table->boolean('sms_notifications')->after('email_notifications')->default(true)->comment('إشعارات الرسائل النصية');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'profile_completed',
                'language',
                'timezone',
                'email_notifications',
                'sms_notifications'
            ]);
        });
    }
};

