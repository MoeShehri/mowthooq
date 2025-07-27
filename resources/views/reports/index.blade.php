@extends('layouts.dashboard')

@section('title', 'التقارير والإحصائيات')

@section('page-header')
<div>
    <h1 class="h2 mb-0">التقارير والإحصائيات</h1>
    <p class="text-muted mb-0">تحليل شامل لبيانات النظام والأداء</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('reports.transactions') }}" class="btn btn-outline-primary">
            <i class="fas fa-file-alt me-1"></i>
            تقرير المعاملات
        </a>
        <a href="{{ route('reports.users') }}" class="btn btn-outline-info">
            <i class="fas fa-users me-1"></i>
            تقرير المستخدمين
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i>
                تصدير التقارير
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('reports.export', 'summary') }}?start_date={{ $startDate }}&end_date={{ $endDate }}">
                    <i class="fas fa-chart-bar me-2"></i>تقرير ملخص
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reports.export', 'transactions') }}?start_date={{ $startDate }}&end_date={{ $endDate }}">
                    <i class="fas fa-file-alt me-2"></i>تقرير المعاملات
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reports.export', 'users') }}?start_date={{ $startDate }}&end_date={{ $endDate }}">
                    <i class="fas fa-users me-2"></i>تقرير المستخدمين
                </a></li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Date Range Filter -->
    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        تحديث التقرير
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <small class="text-muted">
                        الفترة: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </small>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-right-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي المعاملات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_transactions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-right-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">المعاملات المكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['completed_transactions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-right-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">قيد الانتظار</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['pending_transactions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-right-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي المستخدمين</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Transaction Status Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع حالات المعاملات</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Type Distribution -->
        <div class="col-xl-6 col-lg-6">
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع أنواع المستخدمين</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="userTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Daily Transactions Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">المعاملات اليومية</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="col-xl-4 col-lg-5">
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">أكثر المستخدمين نشاطاً</h6>
                </div>
                <div class="card-body">
                    @forelse($topUsers as $user)
                    <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="avatar-sm me-3">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                 class="rounded-circle" width="40" height="40" alt="Avatar">
                            @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; font-size: 16px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->user_type }}</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $user->transactions_count }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p>لا توجد بيانات</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Transaction Types -->
        <div class="col-xl-6 col-lg-6">
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">أنواع المعاملات الأكثر طلباً</h6>
                </div>
                <div class="card-body">
                    @forelse($typeDistribution as $type)
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="fw-bold">{{ $type->type_ar }}</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-info">{{ $type->count }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <p>لا توجد بيانات</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Municipality Distribution -->
        <div class="col-xl-6 col-lg-6">
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع المعاملات حسب البلدية</h6>
                </div>
                <div class="card-body">
                    @forelse($municipalityDistribution as $municipality)
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="fw-bold">{{ $municipality->municipality_ar }}</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">{{ $municipality->count }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-building fa-2x mb-2"></i>
                        <p>لا توجد بيانات</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card dashboard-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">المعاملات الأخيرة</h6>
                <a href="{{ route('reports.transactions') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم المعاملة</th>
                            <th>النوع</th>
                            <th>المستخدم</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('transactions.show', $transaction) }}" class="text-decoration-none">
                                    {{ $transaction->transaction_number }}
                                </a>
                            </td>
                            <td>{{ $transaction->type_ar }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning text-dark',
                                        'under_review' => 'bg-info text-white',
                                        'completed' => 'bg-success text-white',
                                        'rejected' => 'bg-danger text-white',
                                        'cancelled' => 'bg-secondary text-white'
                                    ];
                                    $statusTexts = [
                                        'pending' => 'قيد الانتظار',
                                        'under_review' => 'قيد المراجعة',
                                        'completed' => 'مكتملة',
                                        'rejected' => 'مرفوضة',
                                        'cancelled' => 'ملغاة'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$transaction->status] ?? 'bg-secondary' }}">
                                    {{ $statusTexts[$transaction->status] ?? $transaction->status }}
                                </span>
                            </td>
                            <td>{{ $transaction->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                                    <p>لا توجد معاملات</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-right-primary {
    border-right: 0.25rem solid #4e73df !important;
}
.border-right-success {
    border-right: 0.25rem solid #1cc88a !important;
}
.border-right-warning {
    border-right: 0.25rem solid #f6c23e !important;
}
.border-right-info {
    border-right: 0.25rem solid #36b9cc !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['قيد الانتظار', 'قيد المراجعة', 'مكتملة', 'مرفوضة', 'ملغاة'],
        datasets: [{
            data: [
                {{ $statusDistribution['pending'] ?? 0 }},
                {{ $statusDistribution['under_review'] ?? 0 }},
                {{ $statusDistribution['completed'] ?? 0 }},
                {{ $statusDistribution['rejected'] ?? 0 }},
                {{ $statusDistribution['cancelled'] ?? 0 }}
            ],
            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b', '#6c757d'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// User Type Distribution Chart
const userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
const userTypeChart = new Chart(userTypeCtx, {
    type: 'doughnut',
    data: {
        labels: ['أفراد', 'مكاتب هندسية', 'مطورين عقاريين'],
        datasets: [{
            data: [
                {{ $userTypeDistribution['أفراد'] ?? 0 }},
                {{ $userTypeDistribution['مكاتب هندسية'] ?? 0 }},
                {{ $userTypeDistribution['مطورين عقاريين'] ?? 0 }}
            ],
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Daily Transactions Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyChart = new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($dailyTransactions as $daily)
            '{{ \Carbon\Carbon::parse($daily->date)->format('m/d') }}',
            @endforeach
        ],
        datasets: [{
            label: 'المعاملات اليومية',
            data: [
                @foreach($dailyTransactions as $daily)
                {{ $daily->count }},
                @endforeach
            ],
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush

