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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('معرف المستخدم');
            $table->string('transaction_number')->unique()->comment('رقم المعاملة');
            $table->string('type_ar')->comment('نوع المعاملة');
            $table->string('type_en')->nullable()->comment('Transaction Type (English)');
            $table->enum('status', ['pending', 'under_review', 'completed', 'rejected', 'cancelled'])
                  ->default('pending')->comment('حالة المعاملة');
            $table->string('municipality_ar')->comment('الجهة البلدية');
            $table->string('municipality_en')->nullable()->comment('Municipality (English)');
            $table->text('description_ar')->nullable()->comment('وصف المعاملة');
            $table->text('description_en')->nullable()->comment('Transaction Description (English)');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->text('admin_notes')->nullable()->comment('ملاحظات الإدارة');
            $table->decimal('amount', 10, 2)->nullable()->comment('المبلغ');
            $table->string('reference_number')->nullable()->comment('الرقم المرجعي');
            $table->date('submission_date')->comment('تاريخ التقديم');
            $table->date('expected_completion_date')->nullable()->comment('تاريخ الإنجاز المتوقع');
            $table->date('actual_completion_date')->nullable()->comment('تاريخ الإنجاز الفعلي');
            $table->string('priority')->default('normal')->comment('الأولوية'); // high, normal, low
            $table->json('custom_fields')->nullable()->comment('حقول إضافية');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->comment('مُسند إلى');
            $table->timestamp('last_updated_by_user_at')->nullable()->comment('آخر تحديث من المستخدم');
            $table->timestamp('last_updated_by_admin_at')->nullable()->comment('آخر تحديث من الإدارة');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['municipality_ar', 'status']);
            $table->index('transaction_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
