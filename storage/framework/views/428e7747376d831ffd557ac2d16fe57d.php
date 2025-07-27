<?php $__env->startSection('title', 'طلب تخليص المعاملة'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">طلب تخليص المعاملة</h2>
                    <p class="text-muted mb-0">المعاملة رقم: <?php echo e($clearanceRequest->transaction->transaction_number); ?></p>
                </div>
                <div>
                    <a href="<?php echo e(route('clearance-requests.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Clearance Request Details -->
        <div class="col-lg-8 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-clipboard-check fa-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">تفاصيل طلب التخليص</h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-<?php echo e($clearanceRequest->status_color); ?> me-2">
                                    <i class="fas <?php echo e($clearanceRequest->status_icon); ?> me-1"></i>
                                    <?php echo e($clearanceRequest->status_ar); ?>

                                </span>
                                تاريخ الطلب: <?php echo e($clearanceRequest->created_at->format('Y-m-d H:i')); ?>

                            </p>
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">طلب بواسطة</label>
                            <div class="fw-bold"><?php echo e($clearanceRequest->requestedBy->name); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تاريخ الطلب</label>
                            <div class="fw-bold"><?php echo e($clearanceRequest->created_at->format('Y-m-d H:i')); ?></div>
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div class="mb-4">
                        <label class="form-label text-muted">ملاحظات وتعليقات الموظف</label>
                        <div class="bg-light p-3 rounded">
                            <?php echo e($clearanceRequest->feedback_ar); ?>

                        </div>
                    </div>

                    <?php if($clearanceRequest->status === 'pending'): ?>
                        <!-- Response Form -->
                        <div class="border-top pt-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-reply me-2"></i>
                                ردك على طلب التخليص
                            </h5>
                            
                            <form id="responseForm" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="user_response" class="form-label">تعليقك أو ردك (اختياري)</label>
                                    <textarea class="form-control <?php $__errorArgs = ['user_response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="user_response" name="user_response" rows="4" 
                                              placeholder="يمكنك كتابة أي تعليقات أو توضيحات إضافية..."><?php echo e(old('user_response')); ?></textarea>
                                    <?php $__errorArgs = ['user_response'];
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

                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-success" onclick="submitResponse('approve')">
                                        <i class="fas fa-check me-2"></i>
                                        موافق على التخليص
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="submitResponse('reject')">
                                        <i class="fas fa-times me-2"></i>
                                        رفض التخليص
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Response Display -->
                        <div class="border-top pt-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-reply me-2"></i>
                                ردك على طلب التخليص
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">الحالة</label>
                                    <div>
                                        <span class="badge bg-<?php echo e($clearanceRequest->status_color); ?> fs-6">
                                            <i class="fas <?php echo e($clearanceRequest->status_icon); ?> me-1"></i>
                                            <?php echo e($clearanceRequest->status_ar); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">تاريخ الرد</label>
                                    <div class="fw-bold"><?php echo e($clearanceRequest->responded_at?->format('Y-m-d H:i')); ?></div>
                                </div>
                            </div>

                            <?php if($clearanceRequest->user_response): ?>
                                <div class="mb-3">
                                    <label class="form-label text-muted">ردك</label>
                                    <div class="bg-light p-3 rounded">
                                        <?php echo e($clearanceRequest->user_response); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        <div class="col-lg-4 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-file-alt me-2"></i>
                        ملخص المعاملة
                    </h5>

                    <div class="mb-3">
                        <label class="form-label text-muted">رقم المعاملة</label>
                        <div class="fw-bold"><?php echo e($clearanceRequest->transaction->transaction_number); ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">نوع المعاملة</label>
                        <div class="fw-bold"><?php echo e($clearanceRequest->transaction->type_ar); ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">البلدية/الأمانة</label>
                        <div class="fw-bold"><?php echo e($clearanceRequest->transaction->municipality_ar); ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">الحالة الحالية</label>
                        <div>
                            <span class="badge bg-<?php echo e($clearanceRequest->transaction->status_color); ?> fs-6">
                                <?php echo e($clearanceRequest->transaction->status_ar); ?>

                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">تاريخ التقديم</label>
                        <div class="fw-bold"><?php echo e($clearanceRequest->transaction->submission_date); ?></div>
                    </div>

                    <div class="d-grid">
                        <a href="<?php echo e(route('transactions.show', $clearanceRequest->transaction)); ?>" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>
                            عرض تفاصيل المعاملة
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="modern-card mt-4">
                <div class="card-body">
                    <h6 class="fw-bold text-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات مهمة
                    </h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            الموافقة على التخليص ستنقل المعاملة لمرحلة المراجعة
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            رفض التخليص سيتطلب مراجعة إضافية من الموظف
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-comment text-info me-2"></i>
                            يمكنك إضافة تعليقات توضيحية مع ردك
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
function submitResponse(action) {
    const form = document.getElementById('responseForm');
    const userResponse = document.getElementById('user_response').value;
    
    if (action === 'reject' && !userResponse.trim()) {
        alert('يرجى كتابة سبب الرفض');
        document.getElementById('user_response').focus();
        return;
    }
    
    const actionText = action === 'approve' ? 'الموافقة على' : 'رفض';
    const confirmMessage = `هل أنت متأكد من ${actionText} طلب التخليص؟`;
    
    if (confirm(confirmMessage)) {
        if (action === 'approve') {
            form.action = '<?php echo e(route("clearance-requests.approve", $clearanceRequest)); ?>';
        } else {
            form.action = '<?php echo e(route("clearance-requests.reject", $clearanceRequest)); ?>';
        }
        form.submit();
    }
}

// Auto-hide success/error alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\clearance-requests\show.blade.php ENDPATH**/ ?>