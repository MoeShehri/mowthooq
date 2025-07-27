<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user-specific transaction counts
        if ($user->hasRole('مدير') || $user->hasRole('مدير عام')) {
            // Admin sees all transactions
            $userTransactions = Transaction::count();
            $pendingTransactions = Transaction::where('status', 'قيد الانتظار')->count();
            $underReviewTransactions = Transaction::where('status', 'قيد المراجعة')->count();
            $completedTransactions = Transaction::where('status', 'مكتملة')->count();
            $recentTransactions = Transaction::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        } else {
            // Regular users see only their transactions
            $userTransactions = $user->transactions()->count();
            $pendingTransactions = $user->transactions()->where('status', 'قيد الانتظار')->count();
            $underReviewTransactions = $user->transactions()->where('status', 'قيد المراجعة')->count();
            $completedTransactions = $user->transactions()->where('status', 'مكتملة')->count();
            $recentTransactions = $user->transactions()->orderBy('created_at', 'desc')->limit(10)->get();
        }
        
        return view('dashboard.index', compact(
            'userTransactions',
            'pendingTransactions',
            'underReviewTransactions',
            'completedTransactions',
            'recentTransactions'
        ));
    }

    /**
     * Get dashboard statistics based on user role.
     */
    private function getDashboardStats($user)
    {
        $stats = [];
        
        if ($user->hasRole('admin')) {
            // Admin sees all statistics
            $stats = [
                'total_transactions' => Transaction::count(),
                'pending_transactions' => Transaction::where('status', 'pending')->count(),
                'completed_transactions' => Transaction::where('status', 'completed')->count(),
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'overdue_transactions' => Transaction::overdue()->count(),
            ];
        } elseif ($user->hasRole('developer')) {
            // Developer sees broader statistics
            $stats = [
                'my_transactions' => $user->transactions()->count(),
                'pending_transactions' => $user->transactions()->where('status', 'pending')->count(),
                'completed_transactions' => $user->transactions()->where('status', 'completed')->count(),
                'total_transactions' => Transaction::count(),
                'my_overdue' => $user->transactions()->overdue()->count(),
            ];
        } elseif ($user->hasRole('office')) {
            // Office sees their statistics plus some general info
            $stats = [
                'my_transactions' => $user->transactions()->count(),
                'pending_transactions' => $user->transactions()->where('status', 'pending')->count(),
                'completed_transactions' => $user->transactions()->where('status', 'completed')->count(),
                'under_review' => $user->transactions()->where('status', 'under_review')->count(),
                'my_overdue' => $user->transactions()->overdue()->count(),
            ];
        } else {
            // Individual users see only their statistics
            $stats = [
                'my_transactions' => $user->transactions()->count(),
                'pending_transactions' => $user->transactions()->where('status', 'pending')->count(),
                'completed_transactions' => $user->transactions()->where('status', 'completed')->count(),
                'under_review' => $user->transactions()->where('status', 'under_review')->count(),
            ];
        }
        
        return $stats;
    }

    /**
     * Get recent transactions based on user role.
     */
    private function getRecentTransactions($user)
    {
        $query = Transaction::with(['user', 'assignedTo']);
        
        if (!$user->hasRole('admin')) {
            // Non-admin users see only their transactions
            $query->where('user_id', $user->id);
        }
        
        return $query->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
    }

    /**
     * Get chart data for dashboard.
     */
    private function getChartData($user)
    {
        $months = [];
        $data = [];
        
        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $query = Transaction::whereYear('created_at', $date->year)
                               ->whereMonth('created_at', $date->month);
            
            if (!$user->hasRole('admin')) {
                $query->where('user_id', $user->id);
            }
            
            $data[] = $query->count();
        }
        
        return [
            'months' => $months,
            'data' => $data
        ];
    }

    /**
     * Get quick actions based on user role.
     */
    public function getQuickActions()
    {
        $user = Auth::user();
        $actions = [];
        
        // Common actions for all users
        $actions[] = [
            'title' => 'معاملة جديدة',
            'description' => 'إنشاء معاملة جديدة',
            'icon' => 'fas fa-plus',
            'url' => route('transactions.create'),
            'color' => 'primary'
        ];
        
        $actions[] = [
            'title' => 'معاملاتي',
            'description' => 'عرض جميع معاملاتي',
            'icon' => 'fas fa-file-alt',
            'url' => route('transactions.index'),
            'color' => 'info'
        ];
        
        if ($user->can('view-reports')) {
            $actions[] = [
                'title' => 'التقارير',
                'description' => 'عرض التقارير والإحصائيات',
                'icon' => 'fas fa-chart-bar',
                'url' => route('reports.index'),
                'color' => 'success'
            ];
        }
        
        if ($user->can('view-users')) {
            $actions[] = [
                'title' => 'المستخدمون',
                'description' => 'إدارة المستخدمين',
                'icon' => 'fas fa-users',
                'url' => route('users.index'),
                'color' => 'warning'
            ];
        }
        
        return response()->json($actions);
    }

    /**
     * Get notifications for the current user.
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($notifications);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        
        $user->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
}
