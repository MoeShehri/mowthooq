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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('معرف المستخدم');
            $table->string('type')->comment('نوع الإشعار'); // transaction_update, system_message, etc.
            $table->string('title_ar')->comment('عنوان الإشعار بالعربية');
            $table->string('title_en')->nullable()->comment('Notification Title (English)');
            $table->text('message_ar')->comment('رسالة الإشعار بالعربية');
            $table->text('message_en')->nullable()->comment('Notification Message (English)');
            $table->string('icon')->nullable()->comment('أيقونة الإشعار');
            $table->string('color')->default('primary')->comment('لون الإشعار'); // primary, success, warning, danger, info
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->comment('أولوية الإشعار');
            $table->json('data')->nullable()->comment('بيانات إضافية');
            $table->string('action_url')->nullable()->comment('رابط الإجراء');
            $table->string('action_text_ar')->nullable()->comment('نص الإجراء بالعربية');
            $table->string('action_text_en')->nullable()->comment('Action Text (English)');
            $table->boolean('is_read')->default(false)->comment('مقروء');
            $table->timestamp('read_at')->nullable()->comment('تاريخ القراءة');
            $table->boolean('is_sent')->default(false)->comment('مُرسل');
            $table->timestamp('sent_at')->nullable()->comment('تاريخ الإرسال');
            $table->string('channel')->default('database')->comment('قناة الإرسال'); // database, email, sms
            $table->morphs('notifiable'); // polymorphic relationship for related model
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_read', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['priority', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
