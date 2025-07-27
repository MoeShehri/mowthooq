<?php $__env->startSection('title', 'تفاصيل المستخدم - ' . $user->name); ?>

<?php $__env->startSection('page-header'); ?>
<div>
    <h1 class="h2 mb-0">تفاصيل المستخدم</h1>
    <p class="text-muted mb-0"><?php echo e($user->name); ?></p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للمستخدمين
        </a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-users')): ?>
        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>
            تعديل
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-users')): ?>
        <button type="button" class="btn btn-<?php echo e($user->is_active ? 'danger' : 'success'); ?>" 
                onclick="toggleUserStatus(<?php echo e($user->id); ?>)">
            <i class="fas fa-<?php echo e($user->is_active ? 'ban' : 'check'); ?> me-1"></i>
            <?php echo e($user->is_active ? 'إلغاء تفعيل' : 'تفعيل'); ?>

        </button>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- User Profile -->
        <div class="col-lg-4">
            <div class="card dashboard-card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" 
                             class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                        <?php else: ?>
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 120px; height: 120px; font-size: 48px;">
                            <?php echo e(substr($user->name, 0, 1)); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="card-title"><?php echo e($user->name); ?></h5>
                    <p class="text-muted"><?php echo e($user->email); ?></p>
                    <div class="mb-3">
                        <span class="badge bg-info fs-6"><?php echo e($user->user_type); ?></span>
                        <?php if($user->is_active): ?>
                        <span class="badge bg-success fs-6">نشط</span>
                        <?php else: ?>
                        <span class="badge bg-danger fs-6">غير نشط</span>
                        <?php endif; ?>
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <div class="fw-bold"><?php echo e($user->transactions->count()); ?></div>
                            <div class="text-muted small">المعاملات</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold"><?php echo e($user->notifications->count()); ?></div>
                            <div class="text-muted small">الإشعارات</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold"><?php echo e($user->created_at->diffInDays()); ?></div>
                            <div class="text-muted small">يوم في النظام</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        الأدوار والصلاحيات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">الأدوار</label>
                        <div>
                            <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="badge bg-secondary me-1 mb-1"><?php echo e($role->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-muted">لا توجد أدوار</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-muted">الصلاحيات</label>
                        <div>
                            <?php $__empty_1 = true; $__currentLoopData = $user->getAllPermissions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="badge bg-light text-dark me-1 mb-1"><?php echo e($permission->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-muted">لا توجد صلاحيات</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        المعلومات الشخصية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الاسم الكامل</label>
                            <div class="fw-bold"><?php echo e($user->name); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">البريد الإلكتروني</label>
                            <div class="fw-bold"><?php echo e($user->email); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الهاتف</label>
                            <div class="fw-bold"><?php echo e($user->phone ?: 'غير محدد'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الهوية</label>
                            <div class="fw-bold"><?php echo e($user->national_id ?: 'غير محدد'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تاريخ الميلاد</label>
                            <div class="fw-bold"><?php echo e($user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'غير محدد'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الجنس</label>
                            <div class="fw-bold"><?php echo e($user->gender ?: 'غير محدد'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information (if applicable) -->
            <?php if($user->user_type !== 'أفراد'): ?>
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        معلومات الشركة/المؤسسة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">اسم الشركة</label>
                            <div class="fw-bold"><?php echo e($user->company_name ?: 'غير محدد'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">السجل التجاري</label>
                            <div class="fw-bold"><?php echo e($user->commercial_register ?: 'غير محدد'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">المنصب</label>
                            <div class="fw-bold"><?php echo e($user->position ?: 'غير محدد'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الترخيص</label>
                            <div class="fw-bold"><?php echo e($user->license_number ?: 'غير محدد'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Account Information -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        معلومات الحساب
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تاريخ التسجيل</label>
                            <div class="fw-bold"><?php echo e($user->created_at->format('Y-m-d H:i')); ?></div>
                            <div class="text-muted small"><?php echo e($user->created_at->diffForHumans()); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">آخر تسجيل دخول</label>
                            <?php if($user->last_login_at): ?>
                            <div class="fw-bold"><?php echo e($user->last_login_at->format('Y-m-d H:i')); ?></div>
                            <div class="text-muted small"><?php echo e($user->last_login_at->diffForHumans()); ?></div>
                            <?php else: ?>
                            <div class="text-muted">لم يسجل دخول بعد</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تأكيد البريد الإلكتروني</label>
                            <?php if($user->email_verified_at): ?>
                            <div class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                مؤكد
                            </div>
                            <?php else: ?>
                            <div class="text-warning">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                غير مؤكد
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">حالة الحساب</label>
                            <?php if($user->is_active): ?>
                            <div class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                نشط
                            </div>
                            <?php else: ?>
                            <div class="text-danger">
                                <i class="fas fa-ban me-1"></i>
                                غير نشط
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
                        <h6 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            المعاملات الأخيرة
                        </h6>
                        <a href="<?php echo e(route('transactions.index', ['user_id' => $user->id])); ?>" class="btn btn-sm btn-outline-primary">
                            عرض الكل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $user->transactions()->latest()->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                        <div>
                            <div class="fw-bold"><?php echo e($transaction->transaction_number); ?></div>
                            <div class="text-muted small"><?php echo e($transaction->type_ar); ?></div>
                        </div>
                        <div class="text-end">
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
                            <div class="text-muted small"><?php echo e($transaction->created_at->diffForHumans()); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <p>لا توجد معاملات</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleUserStatus(userId) {
    if (confirm('هل أنت متأكد من تغيير حالة هذا المستخدم؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}/toggle-status`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '<?php echo e(csrf_token()); ?>';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\users\show.blade.php ENDPATH**/ ?>