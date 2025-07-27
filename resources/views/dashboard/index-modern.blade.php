@extends('layouts.dashboard-modern')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
<div class="fade-in">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 fw-bold text-primary">
                                <i class="fas fa-sun text-warning me-2"></i>
                                مرحباً، {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-user-tag me-2"></i>
                                {{ auth()->user()->getRoleNames()->first() ?? 'مستخدم' }} - 
                                آخر تسجيل دخول: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'المرة الأولى' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('transactions.create') }}" class="action-btn pulse">
                                <i class="fas fa-plus"></i>
                                معاملة جديدة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mb-4">
        <div class="quick-action-card" onclick="window.location.href='{{ route('transactions.create') }}'">
            <i class="fas fa-plus-circle"></i>
            <h5>إنشاء معاملة جديدة</h5>
            <p>ابدأ معاملة جديدة وارفق المستندات المطلوبة</p>
        </div>
        
        <div class="quick-action-card" onclick="window.location.href='{{ route('transactions.index') }}'">
            <i class="fas fa-list-alt"></i>
            <h5>عرض جميع المعاملات</h5>
            <p>تصفح وإدارة جميع معاملاتك</p>
        </div>
        
        @can('view-reports')
        <div class="quick-action-card" onclick="window.location.href='{{ route('reports.index') }}'">
            <i class="fas fa-chart-line"></i>
            <h5>التقارير والإحصائيات</h5>
            <p>عرض التقارير التفصيلية والإحصائيات</p>
        </div>
        @endcan
        
        <div class="quick-action-card" onclick="window.location.href='#'">
            <i class="fas fa-headset"></i>
            <h5>الدعم الفني</h5>
            <p>تواصل مع فريق الدعم الفني</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-number">{{ $userTransactions }}</div>
                    <div class="stats-label">
                        @if(auth()->user()->hasRole('مدير') || auth()->user()->hasRole('مدير عام'))
                            إجمالي المعاملات
                        @else
                            معاملاتي
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card warning">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number">{{ $pendingTransactions }}</div>
                    <div class="stats-label">قيد الانتظار</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card info">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="stats-number">{{ $underReviewTransactions }}</div>
                    <div class="stats-label">قيد المراجعة</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card success">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number">{{ $completedTransactions }}</div>
                    <div class="stats-label">مكتملة</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-8 mb-4">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        إحصائيات المعاملات - آخر 6 أشهر
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm active" onclick="updateChart('6months')">
                            6 أشهر
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateChart('year')">
                            سنة
                        </button>
                    </div>
                </div>
                <canvas id="transactionsChart" height="100"></canvas>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="col-lg-4 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-history text-primary me-2"></i>
                        النشاط الأخير
                    </h5>
                    
                    @forelse($recentTransactions as $transaction)
                    <div class="notification-item {{ $transaction->status == 'مرفوضة' ? 'danger' : ($transaction->status == 'مكتملة' ? 'success' : '') }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $transaction->type }}</h6>
                                <p class="mb-1 text-muted small">{{ Str::limit($transaction->description, 50) }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $transaction->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <span class="status-badge {{ strtolower(str_replace(' ', '-', $transaction->status)) }}">
                                {{ $transaction->status }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد معاملات حتى الآن</p>
                        <a href="{{ route('transactions.create') }}" class="action-btn">
                            <i class="fas fa-plus"></i>
                            إنشاء معاملة جديدة
                        </a>
                    </div>
                    @endforelse
                    
                    @if($recentTransactions->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-2"></i>
                            عرض الكل
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-bell text-primary me-2"></i>
                            الإشعارات الأخيرة
                        </h5>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-2"></i>
                            عرض الكل
                        </a>
                    </div>
                    
                    @forelse(auth()->user()->notifications->take(5) as $notification)
                    <div class="notification-item">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $notification->data['title'] ?? 'إشعار جديد' }}</h6>
                                <p class="mb-1 text-muted">{{ $notification->data['message'] ?? 'رسالة الإشعار' }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد إشعارات جديدة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart.js Configuration
const ctx = document.getElementById('transactionsChart').getContext('2d');
const transactionsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['فبراير 2025', 'مارس 2025', 'أبريل 2025', 'مايو 2025', 'يونيو 2025', 'يوليو 2025'],
        datasets: [{
            label: 'المعاملات المكتملة',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: 'rgb(30, 126, 52)',
            backgroundColor: 'rgba(30, 126, 52, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'المعاملات الجديدة',
            data: [2, 3, 20, 5, 1, 4],
            borderColor: 'rgb(255, 193, 7)',
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        family: 'Tajawal, Cairo, sans-serif'
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                },
                ticks: {
                    font: {
                        family: 'Tajawal, Cairo, sans-serif'
                    }
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                },
                ticks: {
                    font: {
                        family: 'Tajawal, Cairo, sans-serif'
                    }
                }
            }
        },
        elements: {
            point: {
                radius: 6,
                hoverRadius: 8
            }
        }
    }
});

// Update chart function
function updateChart(period) {
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Update chart data based on period
    if (period === 'year') {
        transactionsChart.data.labels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        transactionsChart.data.datasets[0].data = [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 80];
        transactionsChart.data.datasets[1].data = [28, 48, 40, 19, 86, 27, 90, 85, 70, 65, 60, 55];
    } else {
        transactionsChart.data.labels = ['فبراير 2025', 'مارس 2025', 'أبريل 2025', 'مايو 2025', 'يونيو 2025', 'يوليو 2025'];
        transactionsChart.data.datasets[0].data = [12, 19, 3, 5, 2, 3];
        transactionsChart.data.datasets[1].data = [2, 3, 20, 5, 1, 4];
    }
    
    transactionsChart.update();
}

// Counter animation for stats
function animateCounters() {
    const counters = document.querySelectorAll('.stats-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

// Trigger counter animation when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(animateCounters, 500);
});

// Add click handlers for quick action cards
document.querySelectorAll('.quick-action-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Auto-refresh notifications every 30 seconds
setInterval(function() {
    // You can implement AJAX call here to refresh notifications
    console.log('Checking for new notifications...');
}, 30000);
</script>
@endpush

