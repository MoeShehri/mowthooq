<?php $__env->startSection('title', 'تقرير المستخدمين'); ?>

<?php $__env->startSection('page-header'); ?>
<div>
    <h1 class="h2 mb-0">تقرير المستخدمين</h1>
    <p class="text-muted mb-0">تقرير تفصيلي لجميع المستخدمين في النظام</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
        <a href="<?php echo e(route('reports.export', 'users')); ?><?php echo e(request()->getQueryString() ? '?' . request()->getQueryString() : ''); ?>" 
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
            <form method="GET" action="<?php echo e(route('reports.users')); ?>" class="row g-3">
                <div class="col-md-3">
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
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>نشط</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label">الدور</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">جميع الأدوار</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->name); ?>" <?php echo e(request('role') == $role->name ? 'selected' : ''); ?>>
                            <?php echo e($role->name); ?>

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
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="<?php echo e(route('reports.users')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card dashboard-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    المستخدمين (<?php echo e($users->total()); ?>)
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
                            <th>المستخدم</th>
                            <th>نوع المستخدم</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>عدد المعاملات</th>
                            <th>تاريخ التسجيل</th>
                            <th>آخر تسجيل دخول</th>
                            <th>معلومات إضافية</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
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
                                    <div>
                                        <div class="fw-bold"><?php echo e($user->name); ?></div>
                                        <div class="text-muted small"><?php echo e($user->email); ?></div>
                                        <?php if($user->phone): ?>
                                        <div class="text-muted small"><?php echo e($user->phone); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($user->user_type); ?></span>
                            </td>
                            <td>
                                <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-secondary me-1"><?php echo e($role->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php if($user->is_active): ?>
                                <span class="badge bg-success">نشط</span>
                                <?php else: ?>
                                <span class="badge bg-danger">غير نشط</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="badge bg-primary fs-6"><?php echo e($user->transactions_count); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="small"><?php echo e($user->created_at->format('Y-m-d')); ?></div>
                                <div class="text-muted small"><?php echo e($user->created_at->diffForHumans()); ?></div>
                            </td>
                            <td>
                                <?php if($user->last_login_at): ?>
                                <div class="small"><?php echo e($user->last_login_at->format('Y-m-d H:i')); ?></div>
                                <div class="text-muted small"><?php echo e($user->last_login_at->diffForHumans()); ?></div>
                                <?php else: ?>
                                <span class="text-muted">لم يسجل دخول</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small">
                                    <?php if($user->national_id): ?>
                                    <div><strong>الهوية:</strong> <?php echo e($user->national_id); ?></div>
                                    <?php endif; ?>
                                    <?php if($user->company_name): ?>
                                    <div><strong>الشركة:</strong> <?php echo e($user->company_name); ?></div>
                                    <?php endif; ?>
                                    <?php if($user->commercial_register): ?>
                                    <div><strong>السجل التجاري:</strong> <?php echo e($user->commercial_register); ?></div>
                                    <?php endif; ?>
                                    <?php if($user->email_verified_at): ?>
                                    <div class="text-success"><i class="fas fa-check-circle me-1"></i>البريد مؤكد</div>
                                    <?php else: ?>
                                    <div class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>البريد غير مؤكد</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('users.show', $user)); ?>" 
                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($user->transactions_count > 0): ?>
                                    <a href="<?php echo e(route('transactions.index', ['user_id' => $user->id])); ?>" 
                                       class="btn btn-sm btn-outline-info" title="المعاملات">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>لا توجد مستخدمين يطابقون معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($users->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($users->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?php echo e($users->total()); ?></h5>
                    <p class="card-text">إجمالي المستخدمين</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><?php echo e($users->where('is_active', true)->count()); ?></h5>
                    <p class="card-text">المستخدمين النشطين</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info"><?php echo e($users->sum('transactions_count')); ?></h5>
                    <p class="card-text">إجمالي المعاملات</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning"><?php echo e($users->where('email_verified_at', null)->count()); ?></h5>
                    <p class="card-text">بريد غير مؤكد</p>
                </div>
            </div>
        </div>
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


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\reports\users.blade.php ENDPATH**/ ?>