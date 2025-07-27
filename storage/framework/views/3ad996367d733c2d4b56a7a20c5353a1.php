<?php $__env->startSection('title', 'التقارير والإحصائيات'); ?>

<?php $__env->startSection('page-header'); ?>
<div>
    <h1 class="h2 mb-0">التقارير والإحصائيات</h1>
    <p class="text-muted mb-0">تحليل شامل لبيانات النظام والأداء</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="<?php echo e(route('reports.transactions')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-file-alt me-1"></i>
            تقرير المعاملات
        </a>
        <a href="<?php echo e(route('reports.users')); ?>" class="btn btn-outline-info">
            <i class="fas fa-users me-1"></i>
            تقرير المستخدمين
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i>
                تصدير التقارير
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo e(route('reports.export', 'summary')); ?>?start_date=<?php echo e($startDate); ?>&end_date=<?php echo e($endDate); ?>">
                    <i class="fas fa-chart-bar me-2"></i>تقرير ملخص
                </a></li>
                <li><a class="dropdown-item" href="<?php echo e(route('reports.export', 'transactions')); ?>?start_date=<?php echo e($startDate); ?>&end_date=<?php echo e($endDate); ?>">
                    <i class="fas fa-file-alt me-2"></i>تقرير المعاملات
                </a></li>
                <li><a class="dropdown-item" href="<?php echo e(route('reports.export', 'users')); ?>?start_date=<?php echo e($startDate); ?>&end_date=<?php echo e($endDate); ?>">
                    <i class="fas fa-users me-2"></i>تقرير المستخدمين
                </a></li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Date Range Filter -->
    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.index')); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e($startDate); ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e($endDate); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        تحديث التقرير
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <small class="text-muted">
                        الفترة: <?php echo e(\Carbon\Carbon::parse($startDate)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('d/m/Y')); ?>

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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['total_transactions'])); ?></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['completed_transactions'])); ?></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['pending_transactions'])); ?></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['total_users'])); ?></div>
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
                    <?php $__empty_1 = true; $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex align-items-center py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                        <div class="avatar-sm me-3">
                            <?php if($user->avatar): ?>
                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" 
                                 class="rounded-circle" width="40" height="40" alt="Avatar">
                            <?php else: ?>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; font-size: 16px;">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold"><?php echo e($user->name); ?></div>
                            <div class="text-muted small"><?php echo e($user->user_type); ?></div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary"><?php echo e($user->transactions_count); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p>لا توجد بيانات</p>
                    </div>
                    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $typeDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                        <div>
                            <div class="fw-bold"><?php echo e($type->type_ar); ?></div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-info"><?php echo e($type->count); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <p>لا توجد بيانات</p>
                    </div>
                    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $municipalityDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $municipality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                        <div>
                            <div class="fw-bold"><?php echo e($municipality->municipality_ar); ?></div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success"><?php echo e($municipality->count); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-building fa-2x mb-2"></i>
                        <p>لا توجد بيانات</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card dashboard-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">المعاملات الأخيرة</h6>
                <a href="<?php echo e(route('reports.transactions')); ?>" class="btn btn-sm btn-outline-primary">
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
                        <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('transactions.show', $transaction)); ?>" class="text-decoration-none">
                                    <?php echo e($transaction->transaction_number); ?>

                                </a>
                            </td>
                            <td><?php echo e($transaction->type_ar); ?></td>
                            <td><?php echo e($transaction->user->name); ?></td>
                            <td>
                                <?php
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
                                ?>
                                <span class="badge <?php echo e($statusClasses[$transaction->status] ?? 'bg-secondary'); ?>">
                                    <?php echo e($statusTexts[$transaction->status] ?? $transaction->status); ?>

                                </span>
                            </td>
                            <td><?php echo e($transaction->created_at->diffForHumans()); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                                    <p>لا توجد معاملات</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
                <?php echo e($statusDistribution['pending'] ?? 0); ?>,
                <?php echo e($statusDistribution['under_review'] ?? 0); ?>,
                <?php echo e($statusDistribution['completed'] ?? 0); ?>,
                <?php echo e($statusDistribution['rejected'] ?? 0); ?>,
                <?php echo e($statusDistribution['cancelled'] ?? 0); ?>

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
                <?php echo e($userTypeDistribution['أفراد'] ?? 0); ?>,
                <?php echo e($userTypeDistribution['مكاتب هندسية'] ?? 0); ?>,
                <?php echo e($userTypeDistribution['مطورين عقاريين'] ?? 0); ?>

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
            <?php $__currentLoopData = $dailyTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daily): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            '<?php echo e(\Carbon\Carbon::parse($daily->date)->format('m/d')); ?>',
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ],
        datasets: [{
            label: 'المعاملات اليومية',
            data: [
                <?php $__currentLoopData = $dailyTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daily): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($daily->count); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\reports\index.blade.php ENDPATH**/ ?>