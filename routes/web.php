<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ClearanceRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Authentication Routes
Auth::routes();

// Redirect /home to dashboard
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/quick-actions', [DashboardController::class, 'getQuickActions'])->name('dashboard.quick-actions');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/api', [DashboardController::class, 'getNotifications'])->name('api');
        Route::post('/{id}/read', [DashboardController::class, 'markNotificationAsRead'])->name('read');
        Route::post('/read-all', [DashboardController::class, 'markAllNotificationsAsRead'])->name('read-all');
    });
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::patch('/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{transaction}/attachments', [TransactionController::class, 'getAttachments'])->name('attachments');
        Route::post('/{transaction}/attachments', [TransactionController::class, 'uploadAttachment'])->name('upload-attachment');
        Route::delete('/attachments/{attachment}', [TransactionController::class, 'deleteAttachment'])->name('delete-attachment');
        Route::get('/{transaction}/simple', function($transaction) {
            $transaction = \App\Models\Transaction::with(['user', 'latestClearanceRequest'])->findOrFail($transaction);
            return view('transactions.show-simple', compact('transaction'));
        })->name('show-simple');
        Route::get('/{transaction}/test', function($transaction) {
            $transaction = \App\Models\Transaction::with(['user', 'latestClearanceRequest'])->findOrFail($transaction);
            return view('transactions.test', compact('transaction'));
        })->name('test');
    });
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/complete', [ProfileController::class, 'completeProfile'])->name('complete');
        Route::post('/complete', [ProfileController::class, 'storeCompleteProfile'])->name('complete.store');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('update-settings');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar.upload');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    });
    
    // Reports (temporarily without permission middleware)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/transactions', [ReportsController::class, 'transactions'])->name('transactions');
        Route::get('/users', [ReportsController::class, 'users'])->name('users');
        Route::get('/export/{type}', [ReportsController::class, 'export'])->name('export');
    });
    
    // User Management (temporarily without permission middleware)
    Route::resource('users', UserController::class);
    Route::prefix('users')->name('users.')->group(function () {
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/send-notification', [UserController::class, 'sendNotification'])->name('send-notification');
    });
    
    // Clearance Requests
    Route::prefix('clearance-requests')->name('clearance-requests.')->group(function () {
        Route::get('/', [ClearanceRequestController::class, 'index'])->name('index');
        Route::get('/{clearanceRequest}', [ClearanceRequestController::class, 'show'])->name('show');
        Route::get('/{clearanceRequest}/respond', [ClearanceRequestController::class, 'showUserResponse'])->name('user-response');
        Route::post('/{clearanceRequest}/respond', [ClearanceRequestController::class, 'respond'])->name('respond');
        Route::get('/admin/all', [ClearanceRequestController::class, 'adminIndex'])->name('admin-index');
    });
    
    // System Settings (temporarily without permission middleware)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/general', [SettingsController::class, 'updateGeneral'])->name('update-general');
        Route::put('/email', [SettingsController::class, 'updateEmail'])->name('update-email');
        Route::get('/logs', [SettingsController::class, 'logs'])->name('logs');
        Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
        Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
        Route::post('/optimize', [SettingsController::class, 'optimize'])->name('optimize');
    });
    
    // File Downloads
    Route::get('/attachments/{attachment}/download', function ($attachment) {
        $attachment = \App\Models\Attachment::findOrFail($attachment);
        
        // Check if user has permission to download this file
        if (!auth()->user()->can('view-transactions') && $attachment->attachable->user_id !== auth()->id()) {
            abort(403);
        }
        
        return response()->download(storage_path('app/' . $attachment->file_path), $attachment->original_name);
    })->name('attachments.download');
});

// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/transactions/search', [TransactionController::class, 'search'])->name('transactions.search');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
});

// Language switching (if needed in the future)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
