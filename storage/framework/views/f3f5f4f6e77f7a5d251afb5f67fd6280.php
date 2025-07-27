<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #006C35 0%, #00582C 100%); color: white;">
                    <h3 class="mb-0 fw-bold"><?php echo e(__('تسجيل الدخول')); ?></h3>
                    <p class="mt-2 mb-0">منصة موثوق - المنصة الرسمية للمعاملات البلدية الرقمية</p>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="email" class="form-label fw-bold"><?php echo e(__('البريد الإلكتروني')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="password" class="form-label fw-bold"><?php echo e(__('كلمة المرور')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="remember">
                                        <?php echo e(__('تذكرني')); ?>

                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12 d-grid">
                                <button type="submit" class="btn btn-success btn-lg" style="background-color: #006C35; border-color: #006C35;">
                                    <i class="fas fa-sign-in-alt me-2"></i><?php echo e(__('تسجيل الدخول')); ?>

                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <?php if(Route::has('password.request')): ?>
                                    <a class="btn btn-link ps-0 text-success" href="<?php echo e(route('password.request')); ?>">
                                        <?php echo e(__('نسيت كلمة المرور؟')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 text-end">
                                <?php if(Route::has('register')): ?>
                                    <a class="btn btn-link pe-0 text-success" href="<?php echo e(route('register')); ?>">
                                        <?php echo e(__('إنشاء حساب جديد')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3" style="background-color: #f8f9fa;">
                    <div class="small">
                        <a href="<?php echo e(route('welcome')); ?>" class="text-decoration-none text-success">
                            <i class="fas fa-arrow-right me-1"></i> العودة إلى الصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views/auth/login.blade.php ENDPATH**/ ?>