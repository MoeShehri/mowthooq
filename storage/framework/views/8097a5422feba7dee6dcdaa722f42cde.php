<?php $__env->startSection('title', 'لوحة التحكم الرئيسية'); ?>

<?php $__env->startSection('page-header'); ?>
<div>
    <h1 class="h2 mb-0">مرحباً، <?php echo e(auth()->user()->display_name); ?></h1>
    <p class="text-muted mb-0"><?php echo e(auth()->user()->user_type_arabic); ?> - آخر تسجيل دخول: <?php echo e(auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'المرة الأولى'); ?></p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="<?php echo e(route('transactions.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            معاملة جديدة
        </a>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i>
            تصدير
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo e(route('reports.export', 'pdf')); ?>">تصدير PDF</a></li>
            <li><a class="dropdown-item" href="<?php echo e(route('reports.export', 'excel')); ?>">تصدير Excel</a></li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <?php if(auth()->user()->hasRole('admin')): ?>
            <!-- Admin Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['total_transactions'])); ?></div>
                                <div class="stats-label">إجمالي المعاملات</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-file-alt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: #000;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['pending_transactions'])); ?></div>
                                <div class="stats-label">معاملات قيد الانتظار</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['completed_transactions'])); ?></div>
                                <div class="stats-label">معاملات مكتملة</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['total_users'])); ?></div>
                                <div class="stats-label">إجمالي المستخدمين</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- User Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['my_transactions'])); ?></div>
                                <div class="stats-label">معاملاتي</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-file-alt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: #000;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['pending_transactions'])); ?></div>
                                <div class="stats-label">قيد الانتظار</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['under_review'] ?? 0)); ?></div>
                                <div class="stats-label">قيد المراجعة</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-search fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stats-number arabic-numbers"><?php echo e(number_format($stats['completed_transactions'])); ?></div>
                                <div class="stats-label">مكتملة</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        إحصائيات المعاملات - آخر 6 أشهر
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active" data-period="6">6 أشهر</button>
                        <button type="button" class="btn btn-outline-primary" data-period="12">سنة</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="transactionsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        إجراءات سريعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('transactions.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            إنشاء معاملة جديدة
                        </a>
                        
                        <a href="<?php echo e(route('transactions.index')); ?>" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>
                            عرض جميع المعاملات
                        </a>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-success">
                            <i class="fas fa-chart-bar me-2"></i>
                            التقارير والإحصائيات
                        </a>
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-users')): ?>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-warning">
                            <i class="fas fa-users me-2"></i>
                            إدارة المستخدمين
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        المعاملات الأخيرة
                    </h5>
                    <a href="<?php echo e(route('transactions.index')); ?>" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                        <i class="fas fa-arrow-left ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($recentTransactions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم المعاملة</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <?php if(auth()->user()->hasRole('admin')): ?>
                                    <th>المستخدم</th>
                                    <?php endif; ?>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold arabic-numbers"><?php echo e($transaction->transaction_number); ?></span>
                                    </td>
                                    <td><?php echo e($transaction->type_ar); ?></td>
                                    <td>
                                        <span class="badge badge-status-<?php echo e($transaction->status); ?>">
                                            <?php echo e($transaction->status_arabic); ?>

                                        </span>
                                    </td>
                                    <td class="text-muted"><?php echo e($transaction->created_at->diffForHumans()); ?></td>
                                    <?php if(auth()->user()->hasRole('admin')): ?>
                                    <td><?php echo e($transaction->user->display_name); ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <a href="<?php echo e(route('transactions.show', $transaction)); ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد معاملات حتى الآن</p>
                        <a href="<?php echo e(route('transactions.create')); ?>" class="btn btn-primary">
                            إنشاء معاملة جديدة
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>
                        الإشعارات الأخيرة
                    </h5>
                    <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($recentNotifications->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item <?php echo e($notification->is_read ? '' : 'bg-light'); ?>">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas <?php echo e($notification->icon ?: 'fa-info-circle'); ?> text-<?php echo e($notification->color); ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 <?php echo e($notification->is_read ? '' : 'fw-bold'); ?>">
                                        <?php echo e($notification->title); ?>

                                    </h6>
                                    <p class="mb-1 small text-muted">
                                        <?php echo e(Str::limit($notification->message, 80)); ?>

                                    </p>
                                    <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">لا توجد إشعارات جديدة</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js configuration
    const ctx = document.getElementById('transactionsChart').getContext('2d');
    const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.months,
            datasets: [{
                label: 'عدد المعاملات',
                data: chartData.data,
                borderColor: '#1e7e34',
                backgroundColor: 'rgba(30, 126, 52, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#1e7e34',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
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
                            family: 'Tajawal'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Tajawal'
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#1e7e34'
                }
            }
        }
    });

    // Period buttons functionality
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('[data-period]').forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you would typically make an AJAX call to get new data
            // For now, we'll just show a loading state
            console.log('Loading data for period:', this.dataset.period);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\dashboard\index-old.blade.php ENDPATH**/ ?>