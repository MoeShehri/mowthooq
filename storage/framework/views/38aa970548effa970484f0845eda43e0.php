<?php $__env->startSection('title', 'الملف الشخصي'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="profile-avatar-large mb-3">
                                <?php if($user->avatar): ?>
                                    <img src="<?php echo e(asset('storage/avatars/' . $user->avatar)); ?>" alt="الصورة الشخصية" class="rounded-circle" width="120" height="120">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?php echo e(substr($user->first_name, 0, 1)); ?><?php echo e(substr($user->last_name, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="profile-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#avatarModal">
                                    <i class="fas fa-camera"></i>
                                    تغيير الصورة
                                </button>
                                <?php if($user->avatar): ?>
                                <form method="POST" action="<?php echo e(route('profile.avatar.delete')); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف الصورة؟')">
                                        <i class="fas fa-trash"></i>
                                        حذف
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h2 class="h3 mb-2 fw-bold text-primary">
                                <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                            </h2>
                            <div class="profile-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="fas fa-user-tag text-muted me-2"></i>
                                            <strong>نوع المستخدم:</strong>
                                            <span class="badge bg-primary ms-2">
                                                <?php switch($user->user_type):
                                                    case ('individual'): ?>
                                                        أفراد
                                                        <?php break; ?>
                                                    <?php case ('office'): ?>
                                                        مكاتب هندسية
                                                        <?php break; ?>
                                                    <?php case ('developer'): ?>
                                                        مطورين عقاريين
                                                        <?php break; ?>
                                                    <?php case ('admin'): ?>
                                                        مدير
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <?php echo e($user->user_type); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-envelope text-muted me-2"></i>
                                            <strong>البريد الإلكتروني:</strong> <?php echo e($user->email); ?>

                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <strong>رقم الهاتف:</strong> <?php echo e($user->phone ?? 'غير محدد'); ?>

                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="fas fa-id-card text-muted me-2"></i>
                                            <strong>رقم الهوية:</strong> <?php echo e($user->national_id ?? 'غير محدد'); ?>

                                        </p>
                                        <?php if($user->user_type === 'office' || $user->user_type === 'developer'): ?>
                                        <p class="mb-2">
                                            <i class="fas fa-building text-muted me-2"></i>
                                            <strong>اسم الشركة:</strong> <?php echo e($user->company_name_ar ?? 'غير محدد'); ?>

                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-certificate text-muted me-2"></i>
                                            <strong>السجل التجاري:</strong> <?php echo e($user->commercial_register ?? 'غير محدد'); ?>

                                        </p>
                                        <?php endif; ?>
                                        <p class="mb-2">
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            <strong>تاريخ التسجيل:</strong> <?php echo e($user->created_at->format('d/m/Y')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-actions mt-3">
                                <a href="<?php echo e(route('profile.edit')); ?>" class="action-btn">
                                    <i class="fas fa-edit"></i>
                                    تعديل الملف الشخصي
                                </a>
                                <button type="button" class="action-btn secondary ms-2" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                    <i class="fas fa-key"></i>
                                    تغيير كلمة المرور
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-number"><?php echo e($user->transactions()->count()); ?></div>
                    <div class="stats-label">إجمالي المعاملات</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card warning">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number"><?php echo e($user->transactions()->where('status', 'قيد الانتظار')->count()); ?></div>
                    <div class="stats-label">قيد الانتظار</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card success">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo e($user->transactions()->where('status', 'مكتملة')->count()); ?></div>
                    <div class="stats-label">مكتملة</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="modern-card stats-card info">
                <div class="card-body position-relative">
                    <div class="stats-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stats-number"><?php echo e($user->notifications()->count()); ?></div>
                    <div class="stats-label">الإشعارات</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-history text-primary me-2"></i>
                        النشاط الأخير
                    </h5>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $user->transactions()->latest()->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="notification-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?php echo e($transaction->type); ?></h6>
                                <p class="mb-1 text-muted small"><?php echo e(Str::limit($transaction->description, 50)); ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo e($transaction->created_at->diffForHumans()); ?>

                                </small>
                            </div>
                            <span class="status-badge <?php echo e(strtolower(str_replace(' ', '-', $transaction->status))); ?>">
                                <?php echo e($transaction->status); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد معاملات حتى الآن</p>
                        <a href="<?php echo e(route('transactions.create')); ?>" class="action-btn">
                            <i class="fas fa-plus"></i>
                            إنشاء معاملة جديدة
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير الصورة الشخصية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('profile.avatar.upload')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="avatar" class="form-label">اختر صورة جديدة</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                        <div class="form-text">الحد الأقصى: 2 ميجابايت. الأنواع المدعومة: JPG, PNG, GIF</div>
                    </div>
                    <div id="imagePreview" class="text-center" style="display: none;">
                        <img id="preview" src="" alt="معاينة" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">رفع الصورة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('profile.password.update')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث كلمة المرور</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.profile-avatar-large {
    position: relative;
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin: 0 auto;
    border: 4px solid rgba(255, 255, 255, 0.3);
}

.profile-info p {
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.profile-info strong {
    color: var(--gray-700);
    font-weight: 600;
}

.profile-actions {
    margin-top: 1rem;
}

.profile-actions .btn {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .profile-actions {
        text-align: center;
        margin-top: 2rem;
    }
    
    .profile-actions .action-btn {
        display: block;
        margin-bottom: 1rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Image preview functionality
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Form validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>جاري المعالجة...';
            submitBtn.disabled = true;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\profile\show.blade.php ENDPATH**/ ?>