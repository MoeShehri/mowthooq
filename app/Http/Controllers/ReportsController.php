<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Temporarily disabled permission middleware
        // $this->middleware('permission:view-reports');
    }

    /**
     * Display the main reports dashboard.
     */
    public function index(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // General Statistics
        $stats = [
            'total_transactions' => Transaction::whereBetween('created_at', [$start, $end])->count(),
            'pending_transactions' => Transaction::whereBetween('created_at', [$start, $end])->where('status', 'pending')->count(),
            'completed_transactions' => Transaction::whereBetween('created_at', [$start, $end])->where('status', 'completed')->count(),
            'rejected_transactions' => Transaction::whereBetween('created_at', [$start, $end])->where('status', 'rejected')->count(),
            'total_users' => User::whereBetween('created_at', [$start, $end])->count(),
            'active_users' => User::whereBetween('created_at', [$start, $end])->where('is_active', true)->count(),
            'total_notifications' => Notification::whereBetween('created_at', [$start, $end])->count(),
        ];

        // Transaction Status Distribution
        $statusDistribution = Transaction::whereBetween('created_at', [$start, $end])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Transaction Types Distribution
        $typeDistribution = Transaction::whereBetween('created_at', [$start, $end])
            ->select('type_ar', DB::raw('count(*) as count'))
            ->groupBy('type_ar')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Municipality Distribution
        $municipalityDistribution = Transaction::whereBetween('created_at', [$start, $end])
            ->select('municipality_ar', DB::raw('count(*) as count'))
            ->groupBy('municipality_ar')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // User Type Distribution
        $userTypeDistribution = User::whereBetween('created_at', [$start, $end])
            ->select('user_type', DB::raw('count(*) as count'))
            ->groupBy('user_type')
            ->get()
            ->pluck('count', 'user_type')
            ->toArray();

        // Daily Transactions Chart Data (last 30 days)
        $dailyTransactions = Transaction::whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly Transactions Chart Data (last 12 months)
        $monthlyTransactions = Transaction::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top Users by Transaction Count
        $topUsers = User::withCount(['transactions' => function($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])
            ->having('transactions_count', '>', 0)
            ->orderBy('transactions_count', 'desc')
            ->limit(10)
            ->get();

        // Recent Activity
        $recentTransactions = Transaction::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'stats',
            'statusDistribution',
            'typeDistribution',
            'municipalityDistribution',
            'userTypeDistribution',
            'dailyTransactions',
            'monthlyTransactions',
            'topUsers',
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display detailed transaction reports.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'assignedTo']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type_ar', 'like', '%' . $request->type . '%');
        }

        if ($request->filled('municipality')) {
            $query->where('municipality_ar', 'like', '%' . $request->municipality . '%');
        }

        if ($request->filled('user_type')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('user_type', $request->user_type);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $statuses = Transaction::distinct()->pluck('status');
        $types = Transaction::distinct()->pluck('type_ar')->filter();
        $municipalities = Transaction::distinct()->pluck('municipality_ar')->filter();
        $userTypes = User::distinct()->pluck('user_type')->filter();

        return view('reports.transactions', compact(
            'transactions',
            'statuses',
            'types',
            'municipalities',
            'userTypes'
        ));
    }

    /**
     * Display detailed user reports.
     */
    public function users(Request $request)
    {
        $query = User::withCount('transactions');

        // Apply filters
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $userTypes = User::distinct()->pluck('user_type')->filter();
        $roles = \Spatie\Permission\Models\Role::all();

        return view('reports.users', compact(
            'users',
            'userTypes',
            'roles'
        ));
    }

    /**
     * Export reports data.
     */
    public function export(Request $request, $type)
    {
        switch ($type) {
            case 'transactions':
                return $this->exportTransactions($request);
            case 'users':
                return $this->exportUsers($request);
            case 'summary':
                return $this->exportSummary($request);
            default:
                abort(404);
        }
    }

    /**
     * Export transactions data to CSV.
     */
    private function exportTransactions(Request $request)
    {
        $query = Transaction::with(['user', 'assignedTo']);

        // Apply same filters as in transactions method
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type_ar', 'like', '%' . $request->type . '%');
        }

        if ($request->filled('municipality')) {
            $query->where('municipality_ar', 'like', '%' . $request->municipality . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'transactions_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'رقم المعاملة',
                'نوع المعاملة',
                'البلدية',
                'المستخدم',
                'نوع المستخدم',
                'الحالة',
                'الأولوية',
                'تاريخ الإنشاء',
                'تاريخ آخر تحديث',
                'المسؤول المكلف'
            ]);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_number,
                    $transaction->type_ar,
                    $transaction->municipality_ar,
                    $transaction->user->name,
                    $transaction->user->user_type,
                    $transaction->status,
                    $transaction->priority,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->updated_at->format('Y-m-d H:i:s'),
                    $transaction->assignedTo?->name ?? 'غير محدد'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export users data to CSV.
     */
    private function exportUsers(Request $request)
    {
        $query = User::withCount('transactions');

        // Apply same filters as in users method
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'الاسم',
                'البريد الإلكتروني',
                'الهاتف',
                'نوع المستخدم',
                'الحالة',
                'عدد المعاملات',
                'تاريخ التسجيل',
                'آخر تسجيل دخول'
            ]);

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->phone ?? 'غير محدد',
                    $user->user_type,
                    $user->is_active ? 'نشط' : 'غير نشط',
                    $user->transactions_count,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at?->format('Y-m-d H:i:s') ?? 'لم يسجل دخول'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export summary report to CSV.
     */
    private function exportSummary(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $filename = 'summary_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($start, $end) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Summary statistics
            fputcsv($file, ['تقرير ملخص النظام']);
            fputcsv($file, ['الفترة:', $start->format('Y-m-d') . ' إلى ' . $end->format('Y-m-d')]);
            fputcsv($file, []);
            
            // Transaction statistics
            fputcsv($file, ['إحصائيات المعاملات']);
            fputcsv($file, ['إجمالي المعاملات', Transaction::whereBetween('created_at', [$start, $end])->count()]);
            fputcsv($file, ['المعاملات المكتملة', Transaction::whereBetween('created_at', [$start, $end])->where('status', 'completed')->count()]);
            fputcsv($file, ['المعاملات قيد الانتظار', Transaction::whereBetween('created_at', [$start, $end])->where('status', 'pending')->count()]);
            fputcsv($file, ['المعاملات المرفوضة', Transaction::whereBetween('created_at', [$start, $end])->where('status', 'rejected')->count()]);
            fputcsv($file, []);
            
            // User statistics
            fputcsv($file, ['إحصائيات المستخدمين']);
            fputcsv($file, ['إجمالي المستخدمين', User::whereBetween('created_at', [$start, $end])->count()]);
            fputcsv($file, ['المستخدمين النشطين', User::whereBetween('created_at', [$start, $end])->where('is_active', true)->count()]);
            fputcsv($file, []);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

