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
        Schema::create('clearance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade')->comment('معرف المعاملة');
            $table->foreignId('requested_by')->constrained('users')->comment('طلب بواسطة (الموظف)');
            $table->text('feedback_ar')->comment('الملاحظات والتعليقات بالعربية');
            $table->text('feedback_en')->nullable()->comment('Feedback in English');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('حالة طلب التخليص');
            $table->text('user_response')->nullable()->comment('رد المستخدم');
            $table->timestamp('responded_at')->nullable()->comment('تاريخ الرد');
            $table->foreignId('responded_by')->nullable()->constrained('users')->comment('رد بواسطة');
            $table->json('attachments')->nullable()->comment('مرفقات إضافية');
            $table->timestamps();
            
            // Indexes
            $table->index(['transaction_id', 'status']);
            $table->index(['requested_by', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearance_requests');
    }
};

