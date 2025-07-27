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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable'); // polymorphic relationship (attachable_id, attachable_type)
            $table->foreignId('uploaded_by')->constrained('users')->comment('رفع بواسطة');
            $table->string('original_name')->comment('الاسم الأصلي للملف');
            $table->string('file_name')->comment('اسم الملف المحفوظ');
            $table->string('file_path')->comment('مسار الملف');
            $table->string('mime_type')->comment('نوع الملف');
            $table->string('extension')->comment('امتداد الملف');
            $table->unsignedBigInteger('file_size')->comment('حجم الملف بالبايت');
            $table->string('category')->nullable()->comment('فئة الملف'); // document, image, etc.
            $table->text('description_ar')->nullable()->comment('وصف الملف بالعربية');
            $table->text('description_en')->nullable()->comment('File Description (English)');
            $table->boolean('is_public')->default(false)->comment('ملف عام');
            $table->boolean('is_verified')->default(false)->comment('ملف موثق');
            $table->timestamp('verified_at')->nullable()->comment('تاريخ التوثيق');
            $table->foreignId('verified_by')->nullable()->constrained('users')->comment('موثق بواسطة');
            $table->json('metadata')->nullable()->comment('بيانات إضافية');
            $table->timestamps();
            
            // Indexes
            $table->index(['uploaded_by', 'created_at']);
            $table->index(['category', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
