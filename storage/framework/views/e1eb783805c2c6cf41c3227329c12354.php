<?php $__env->startSection('title', 'إكمال الملف الشخصي'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="modern-card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-primary">مرحباً <?php echo e($user->first_name); ?>!</h3>
                        <p class="text-muted">يرجى إكمال معلوماتك الأساسية للمتابعة</p>
                    </div>

                    <form method="POST" action="<?php echo e(route('profile.complete.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <!-- Essential Information Only -->
                        <div class="text-start">
                            <div class="mb-3">
                                <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="phone" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" 
                                       placeholder="05xxxxxxxx" required>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <?php if($user->user_type === 'office' || $user->user_type === 'developer'): ?>
                            <!-- Company Information (Simplified) -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>معلومات إضافية مطلوبة:</strong> نظراً لأنك مسجل كـ
                                <?php if($user->user_type === 'office'): ?>
                                    "مكتب هندسي"
                                <?php else: ?>
                                    "مطور عقاري"
                                <?php endif; ?>
                                ، يرجى إدخال المعلومات التالية:
                            </div>

                            <div class="mb-3">
                                <label for="company_name_ar" class="form-label">
                                    <?php if($user->user_type === 'office'): ?>
                                        اسم المكتب الهندسي
                                    <?php else: ?>
                                        اسم الشركة
                                    <?php endif; ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['company_name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="company_name_ar" name="company_name_ar" value="<?php echo e(old('company_name_ar', $user->company_name_ar)); ?>" required>
                                <?php $__errorArgs = ['company_name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="commercial_register" class="form-label">السجل التجاري <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['commercial_register'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="commercial_register" name="commercial_register" value="<?php echo e(old('commercial_register', $user->commercial_register)); ?>" 
                                       placeholder="رقم السجل التجاري" required>
                                <?php $__errorArgs = ['commercial_register'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <?php endif; ?>

                            <!-- Optional Fields -->
                            <div class="mb-3">
                                <label for="city_ar" class="form-label">المدينة (اختياري)</label>
                                <select class="form-select <?php $__errorArgs = ['city_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="city_ar" name="city_ar">
                                    <option value="">اختر المدينة</option>
                                    <option value="الرياض" <?php echo e(old('city_ar', $user->city_ar) == 'الرياض' ? 'selected' : ''); ?>>الرياض</option>
                                    <option value="جدة" <?php echo e(old('city_ar', $user->city_ar) == 'جدة' ? 'selected' : ''); ?>>جدة</option>
                                    <option value="مكة المكرمة" <?php echo e(old('city_ar', $user->city_ar) == 'مكة المكرمة' ? 'selected' : ''); ?>>مكة المكرمة</option>
                                    <option value="المدينة المنورة" <?php echo e(old('city_ar', $user->city_ar) == 'المدينة المنورة' ? 'selected' : ''); ?>>المدينة المنورة</option>
                                    <option value="الدمام" <?php echo e(old('city_ar', $user->city_ar) == 'الدمام' ? 'selected' : ''); ?>>الدمام</option>
                                    <option value="الخبر" <?php echo e(old('city_ar', $user->city_ar) == 'الخبر' ? 'selected' : ''); ?>>الخبر</option>
                                    <option value="الطائف" <?php echo e(old('city_ar', $user->city_ar) == 'الطائف' ? 'selected' : ''); ?>>الطائف</option>
                                    <option value="بريدة" <?php echo e(old('city_ar', $user->city_ar) == 'بريدة' ? 'selected' : ''); ?>>بريدة</option>
                                    <option value="تبوك" <?php echo e(old('city_ar', $user->city_ar) == 'تبوك' ? 'selected' : ''); ?>>تبوك</option>
                                    <option value="خميس مشيط" <?php echo e(old('city_ar', $user->city_ar) == 'خميس مشيط' ? 'selected' : ''); ?>>خميس مشيط</option>
                                </select>
                                <?php $__errorArgs = ['city_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <label for="address_ar" class="form-label">العنوان (اختياري)</label>
                                <textarea class="form-control <?php $__errorArgs = ['address_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="address_ar" name="address_ar" rows="2" 
                                          placeholder="العنوان التفصيلي"><?php echo e(old('address_ar', $user->address_ar)); ?></textarea>
                                <?php $__errorArgs = ['address_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="action-btn pulse">
                                <i class="fas fa-check me-2"></i>
                                إكمال الملف الشخصي والمتابعة
                            </button>
                            <small class="text-muted mt-2">
                                يمكنك تعديل هذه المعلومات لاحقاً من الملف الشخصي
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="modern-card mt-4">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-question-circle me-2"></i>
                        لماذا نحتاج هذه المعلومات؟
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>رقم الهاتف:</strong> للتواصل معك بخصوص المعاملات
                        </li>
                        <?php if($user->user_type === 'office' || $user->user_type === 'developer'): ?>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>معلومات الشركة:</strong> مطلوبة للتحقق من صحة البيانات
                        </li>
                        <?php endif; ?>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>المدينة والعنوان:</strong> لتسهيل عملية معالجة المعاملات
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.startsWith('966')) {
        value = value.substring(3);
    }
    if (value.startsWith('0')) {
        value = value.substring(1);
    }
    if (value.length > 0 && !value.startsWith('5')) {
        value = '5' + value;
    }
    if (value.length > 0) {
        value = '0' + value;
    }
    if (value.length > 10) {
        value = value.substring(0, 10);
    }
    e.target.value = value;
});

// Commercial register formatting
const commercialRegister = document.getElementById('commercial_register');
if (commercialRegister) {
    commercialRegister.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        e.target.value = value;
    });
}

// Form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>جاري الحفظ...';
    submitBtn.disabled = true;
});

// Auto-focus on phone field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('phone').focus();
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\profile\complete.blade.php ENDPATH**/ ?>