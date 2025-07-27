<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, create roles and permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'name_ar' => 'المدير العام',
            'email' => 'admin@mowthook.sa',
            'phone' => '966501234567',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'national_id' => '1234567890',
            'city_ar' => 'الرياض',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create sample individual user
        $individual = User::create([
            'name' => 'Ahmed Al-Saudi',
            'name_ar' => 'أحمد السعودي',
            'email' => 'ahmed@example.com',
            'phone' => '966501234568',
            'password' => Hash::make('password123'),
            'user_type' => 'individual',
            'national_id' => '1234567891',
            'city_ar' => 'جدة',
            'is_active' => true,
        ]);
        $individual->assignRole('individual');

        // Create sample office user
        $office = User::create([
            'name' => 'Engineering Office Manager',
            'name_ar' => 'مدير المكتب الهندسي',
            'email' => 'office@example.com',
            'phone' => '966501234569',
            'password' => Hash::make('password123'),
            'user_type' => 'office',
            'national_id' => '1234567892',
            'company_name_ar' => 'مكتب الهندسة المتقدمة',
            'commercial_register' => '1010123456',
            'city_ar' => 'الدمام',
            'is_active' => true,
        ]);
        $office->assignRole('office');

        // Create sample developer user
        $developer = User::create([
            'name' => 'Real Estate Developer',
            'name_ar' => 'مطور عقاري',
            'email' => 'developer@example.com',
            'phone' => '966501234570',
            'password' => Hash::make('password123'),
            'user_type' => 'developer',
            'national_id' => '1234567893',
            'company_name_ar' => 'شركة التطوير العقاري المتميز',
            'commercial_register' => '1010123457',
            'city_ar' => 'مكة المكرمة',
            'is_active' => true,
        ]);
        $developer->assignRole('developer');

        $this->command->info('Sample users created successfully!');
        $this->command->info('Admin: admin@mowthook.sa / admin123');
        $this->command->info('Individual: ahmed@example.com / password123');
        $this->command->info('Office: office@example.com / password123');
        $this->command->info('Developer: developer@example.com / password123');
    }
}
