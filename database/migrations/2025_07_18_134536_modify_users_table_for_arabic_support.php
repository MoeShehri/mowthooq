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
            $table->string('name_ar')->after('name')->nullable()->comment('الاسم بالعربية');
            $table->string('phone')->after('email')->nullable()->comment('رقم الهاتف');
            $table->enum('user_type', ['individual', 'office', 'developer', 'admin'])
                  ->after('phone')->default('individual')->comment('نوع المستخدم');
            $table->string('national_id')->after('user_type')->nullable()->comment('رقم الهوية');
            $table->string('company_name_ar')->after('national_id')->nullable()->comment('اسم الشركة بالعربية');
            $table->string('commercial_register')->after('company_name_ar')->nullable()->comment('السجل التجاري');
            $table->text('address_ar')->after('commercial_register')->nullable()->comment('العنوان بالعربية');
            $table->string('city_ar')->after('address_ar')->nullable()->comment('المدينة بالعربية');
            $table->boolean('is_active')->after('city_ar')->default(true)->comment('حالة النشاط');
            $table->timestamp('last_login_at')->after('is_active')->nullable()->comment('آخر تسجيل دخول');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar',
                'phone',
                'user_type',
                'national_id',
                'company_name_ar',
                'commercial_register',
                'address_ar',
                'city_ar',
                'is_active',
                'last_login_at'
            ]);
        });
    }
};
