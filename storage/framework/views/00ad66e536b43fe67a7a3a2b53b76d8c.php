<?php $__env->startSection('title', 'تقرير المعاملات'); ?>

<?php $__env->startSection('page-header'); ?>
<div>
    <h1 class="h2 mb-0">تقرير المعاملات</h1>
    <p class="text-muted mb-0">تقرير تفصيلي لجميع المعاملات في النظام</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
        <a href="<?php echo e(route('reports.export', 'transactions')); ?><?php echo e(request()->getQueryString() ? '?' . request()->getQueryString() : ''); ?>" 
           class="btn btn-success">
            <i class="fas fa-download me-1"></i>
            تصدير Excel
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Filters -->
    <div class="card dashboard-card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                فلاتر البحث
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.transactions')); ?>" class="row g-3">
                <div class="col-md-2">
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                            <?php switch($status):
                                case ('pending'): ?> قيد الانتظار <?php break; ?>
                                <?php case ('under_review'): ?> قيد المراجعة <?php break; ?>
                                <?php case ('completed'): ?> مكتملة <?php break; ?>
                                <?php case ('rejected'): ?> مرفوضة <?php break; ?>
                                <?php case ('cancelled'): ?> ملغاة <?php break; ?>
                                <?php default: ?> <?php echo e($status); ?>

                            <?php endswitch; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">نوع المعاملة</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">جميع الأنواع</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>" <?php echo e(request('type') == $type ? 'selected' : ''); ?>>
                            <?php echo e($type); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="municipality" class="form-label">البلدية</label>
                    <select class="form-select" id="municipality" name="municipality">
                        <option value="">جميع البلديات</option>
                        <?php $__currentLoopData = $municipalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $municipality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($municipality); ?>" <?php echo e(request('municipality') == $municipality ? 'selected' : ''); ?>>
                            <?php echo e($municipality); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="user_type" class="form-label">نوع المستخدم</label>
                    <select class="form-select" id="user_type" name="user_type">
                        <option value="">جميع الأنواع</option>
                        <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($userType); ?>" <?php echo e(request('user_type') == $userType ? 'selected' : ''); ?>>
                            <?php echo e($userType); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>">
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="<?php echo e(route('reports.transactions')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            إلغاء الفلاتر
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card dashboard-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    المعاملات (<?php echo e($transactions->total()); ?>)
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم المعاملة</th>
                            <th>نوع المعاملة</th>
                            <th>البلدية</th>
                            <th>المستخدم</th>
                            <th>نوع المستخدم</th>
                            <th>الحالة</th>
                            <th>الأولوية</th>
                            <th>تاريخ الإنشاء</th>
                            <th>آخر تحديث</th>
                            <th>المسؤول</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('transactions.show', $transaction)); ?>" class="text-decoration-none fw-bold">
                                    <?php echo e($transaction->transaction_number); ?>

                                </a>
                            </td>
                            <td><?php echo e($transaction->type_ar); ?></td>
                            <td><?php echo e($transaction->municipality_ar); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <?php if($transaction->user->avatar): ?>
                                        <img src="<?php echo e(asset('storage/' . $transaction->user->avatar)); ?>" 
                                             class="rounded-circle" width="30" height="30" alt="Avatar">
                                        <?php else: ?>
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            <?php echo e(substr($transaction->user->name, 0, 1)); ?>

                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo e($transaction->user->name); ?></div>
                                        <div class="text-muted small"><?php echo e($transaction->user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($transaction->user->user_type); ?></span>
                            </td>
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
                            <td>
                                <?php
                                    $priorityClasses = [
                                        'low' => 'bg-light text-dark',
                                        'normal' => 'bg-primary',
                                        'high' => 'bg-warning text-dark',
                                        'urgent' => 'bg-danger'
                                    ];
                                    $priorityTexts = [
                                        'low' => 'منخفضة',
                                        'normal' => 'عادية',
                                        'high' => 'عالية',
                                        'urgent' => 'عاجلة'
                                    ];
                                ?>
                                <span class="badge <?php echo e($priorityClasses[$transaction->priority] ?? 'bg-primary'); ?>">
                                    <?php echo e($priorityTexts[$transaction->priority] ?? $transaction->priority); ?>

                                </span>
                            </td>
                            <td>
                                <div class="small"><?php echo e($transaction->created_at->format('Y-m-d')); ?></div>
                                <div class="text-muted small"><?php echo e($transaction->created_at->format('H:i')); ?></div>
                            </td>
                            <td>
                                <div class="small"><?php echo e($transaction->updated_at->format('Y-m-d')); ?></div>
                                <div class="text-muted small"><?php echo e($transaction->updated_at->diffForHumans()); ?></div>
                            </td>
                            <td>
                                <?php if($transaction->assignedTo): ?>
                                <div class="small fw-bold"><?php echo e($transaction->assignedTo->name); ?></div>
                                <?php else: ?>
                                <span class="text-muted">غير محدد</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('transactions.show', $transaction)); ?>" 
                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($transaction->attachments->count() > 0): ?>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            title="المرفقات (<?php echo e($transaction->attachments->count()); ?>)">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <p>لا توجد معاملات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($transactions->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($transactions->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    .btn, .card-header .d-flex > div:last-child, .pagination {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\reports\transactions.blade.php ENDPATH**/ ?>