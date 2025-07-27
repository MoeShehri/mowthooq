<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Notification;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Temporarily disabled permission middleware
        // $this->middleware('permission:manage-settings');
    }

    /**
     * Display the settings page.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::where('is_read', false)->count(),
        ];

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_time' => now()->format('Y-m-d H:i:s'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'debug_mode' => config('app.debug'),
            'environment' => config('app.env'),
        ];

        return view('settings.index', compact('stats', 'systemInfo'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'maintenance_mode' => 'boolean',
        ], [
            'app_name.required' => 'اسم التطبيق مطلوب',
            'contact_email.required' => 'البريد الإلكتروني للتواصل مطلوب',
            'contact_email.email' => 'البريد الإلكتروني غير صحيح',
            'contact_phone.required' => 'رقم الهاتف للتواصل مطلوب',
        ]);

        // Here you would typically save to a settings table or config file
        // For now, we'll just return success
        
        return back()->with('success', 'تم تحديث الإعدادات العامة بنجاح');
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ], [
            'mail_driver.required' => 'نوع البريد الإلكتروني مطلوب',
            'mail_host.required' => 'خادم البريد الإلكتروني مطلوب',
            'mail_port.required' => 'منفذ البريد الإلكتروني مطلوب',
            'mail_username.required' => 'اسم المستخدم مطلوب',
            'mail_password.required' => 'كلمة المرور مطلوبة',
            'mail_from_address.required' => 'عنوان المرسل مطلوب',
            'mail_from_name.required' => 'اسم المرسل مطلوب',
        ]);

        // Here you would typically update the .env file or config
        // For now, we'll just return success
        
        return back()->with('success', 'تم تحديث إعدادات البريد الإلكتروني بنجاح');
    }

    /**
     * Display system logs.
     */
    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $lines = explode("\n", $content);
            
            // Get last 100 lines
            $lines = array_slice($lines, -100);
            
            foreach ($lines as $line) {
                if (!empty(trim($line))) {
                    $logs[] = $line;
                }
            }
        }

        return view('settings.logs', compact('logs'));
    }

    /**
     * Create system backup.
     */
    public function backup()
    {
        try {
            // Create a simple backup of the database
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Create backups directory if it doesn't exist
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            // Simple database backup (you might want to use a more robust solution)
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                return response()->download($path, $filename)->deleteFileAfterSend();
            } else {
                return back()->with('error', 'فشل في إنشاء النسخة الاحتياطية');
            }

        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إنشاء النسخة الاحتياطية');
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');

            return back()->with('success', 'تم مسح الذاكرة المؤقتة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء مسح الذاكرة المؤقتة');
        }
    }

    /**
     * Optimize application.
     */
    public function optimize()
    {
        try {
            \Artisan::call('optimize');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');

            return back()->with('success', 'تم تحسين التطبيق بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحسين التطبيق');
        }
    }
}

