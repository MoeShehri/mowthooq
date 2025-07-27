<?php $__env->startSection('title', 'المعاملات'); ?>

<?php $__env->startSection('page-header'); ?>
<div>
    <h1 class="h2 mb-0">المعاملات</h1>
    <p class="text-muted mb-0">إدارة ومتابعة جميع المعاملات</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="<?php echo e(route('transactions.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            معاملة جديدة
        </a>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter me-1"></i>
            تصفية
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="exportTransactions()">
            <i class="fas fa-download me-1"></i>
            تصدير
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="<?php echo e(route('transactions.index')); ?>" class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="البحث في المعاملات..." 
                           value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="d-flex gap-2">
                <select class="form-select" name="status" onchange="filterTransactions()">
                    <option value="">جميع الحالات</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>قيد الانتظار</option>
                    <option value="under_review" <?php echo e(request('status') == 'under_review' ? 'selected' : ''); ?>>قيد المراجعة</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>مكتملة</option>
                    <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>مرفوضة</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>ملغاة</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="stat-icon bg-primary text-white rounded-circle me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo e($transactions->total()); ?></h3>
                            <p class="text-muted mb-0">إجمالي المعاملات</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="stat-icon bg-warning text-white rounded-circle me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo e($transactions->where('status', 'pending')->count()); ?></h3>
                            <p class="text-muted mb-0">قيد الانتظار</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="stat-icon bg-info text-white rounded-circle me-3">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo e($transactions->where('status', 'under_review')->count()); ?></h3>
                            <p class="text-muted mb-0">قيد المراجعة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="stat-icon bg-success text-white rounded-circle me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo e($transactions->where('status', 'completed')->count()); ?></h3>
                            <p class="text-muted mb-0">مكتملة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card dashboard-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة المعاملات
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if($transactions->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>رقم المعاملة</th>
                                <th>نوع المعاملة</th>
                                <th>البلدية</th>
                                <?php if(auth()->user()->hasRole('admin')): ?>
                                    <th>المستخدم</th>
                                <?php endif; ?>
                                <th>الحالة</th>
                                <th>الأولوية</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('transactions.show', $transaction)); ?>" 
                                           class="text-decoration-none fw-bold">
                                            <?php echo e($transaction->transaction_number); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($transaction->type_ar); ?></td>
                                    <td><?php echo e($transaction->municipality_ar); ?></td>
                                    <?php if(auth()->user()->hasRole('admin')): ?>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <?php echo e(substr($transaction->user->display_name, 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo e($transaction->user->display_name); ?></div>
                                                    <small class="text-muted"><?php echo e($transaction->user->email); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                    <?php endif; ?>
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
                                                'low' => 'text-success',
                                                'normal' => 'text-primary',
                                                'high' => 'text-warning',
                                                'urgent' => 'text-danger'
                                            ];
                                            $priorityTexts = [
                                                'low' => 'منخفضة',
                                                'normal' => 'عادية',
                                                'high' => 'عالية',
                                                'urgent' => 'عاجلة'
                                            ];
                                        ?>
                                        <span class="<?php echo e($priorityClasses[$transaction->priority] ?? 'text-secondary'); ?>">
                                            <i class="fas fa-circle me-1"></i>
                                            <?php echo e($priorityTexts[$transaction->priority] ?? $transaction->priority); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div><?php echo e($transaction->created_at->format('Y/m/d')); ?></div>
                                        <small class="text-muted"><?php echo e($transaction->created_at->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('transactions.show', $transaction)); ?>" 
                                               class="btn btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if($transaction->user_id == auth()->id() && $transaction->status == 'pending'): ?>
                                                <a href="<?php echo e(route('transactions.edit', $transaction)); ?>" 
                                                   class="btn btn-outline-secondary" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(auth()->user()->hasRole('admin')): ?>
                                                <button type="button" class="btn btn-outline-success" 
                                                        onclick="updateStatus(<?php echo e($transaction->id); ?>, 'completed')" 
                                                        title="إنجاز">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="showRejectModal(<?php echo e($transaction->id); ?>)" 
                                                        title="رفض">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer">
                    <?php echo e($transactions->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد معاملات</h5>
                    <p class="text-muted">لم يتم العثور على أي معاملات تطابق معايير البحث</p>
                    <a href="<?php echo e(route('transactions.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        إنشاء معاملة جديدة
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفض المعاملة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">سبب الرفض <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" placeholder="اكتب سبب رفض المعاملة..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">ملاحظات إضافية</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" 
                                  rows="3" placeholder="أي ملاحظات إضافية..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">رفض المعاملة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تصفية المعاملات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="<?php echo e(route('transactions.index')); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filter_status" class="form-label">الحالة</label>
                            <select class="form-select" name="status" id="filter_status">
                                <option value="">جميع الحالات</option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>قيد الانتظار</option>
                                <option value="under_review" <?php echo e(request('status') == 'under_review' ? 'selected' : ''); ?>>قيد المراجعة</option>
                                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>مكتملة</option>
                                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>مرفوضة</option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>ملغاة</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filter_type" class="form-label">نوع المعاملة</label>
                            <input type="text" class="form-control" name="type" id="filter_type" 
                                   value="<?php echo e(request('type')); ?>" placeholder="نوع المعاملة">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="filter_municipality" class="form-label">البلدية</label>
                        <input type="text" class="form-control" name="municipality" id="filter_municipality" 
                               value="<?php echo e(request('municipality')); ?>" placeholder="البلدية">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <a href="<?php echo e(route('transactions.index')); ?>" class="btn btn-outline-secondary">إعادة تعيين</a>
                    <button type="submit" class="btn btn-primary">تطبيق التصفية</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function updateStatus(transactionId, status) {
    if (confirm('هل أنت متأكد من تغيير حالة المعاملة؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/transactions/${transactionId}/status`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '<?php echo e(csrf_token()); ?>';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal(transactionId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = `/transactions/${transactionId}/status`;
    modal.show();
}

function filterTransactions() {
    const status = document.querySelector('select[name="status"]').value;
    const url = new URL(window.location);
    
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    window.location = url;
}

function exportTransactions() {
    // Implement export functionality
    alert('سيتم تنفيذ وظيفة التصدير قريباً');
}
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\transactions\index.blade.php ENDPATH**/ ?>