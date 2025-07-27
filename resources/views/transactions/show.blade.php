@extends('layouts.dashboard')

@section('title', 'تفاصيل المعاملة - ' . $transaction->transaction_number)

@section('page-header')
<div>
    <h1 class="h2 mb-0">تفاصيل المعاملة</h1>
    <p class="text-muted mb-0">{{ $transaction->transaction_number }}</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للمعاملات
        </a>
        @if($transaction->user_id == auth()->id() && $transaction->status == 'pending')
            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>
                تعديل
            </a>
        @endif
        {{-- Temporarily removed role check --}}
        @if(true)
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-1"></i>
                    إدارة المعاملة
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="updateStatus('under_review')">
                        <i class="fas fa-eye me-2"></i>قيد المراجعة
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="updateStatus('completed')">
                        <i class="fas fa-check me-2"></i>إنجاز المعاملة
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="showClearanceModal()">
                        <i class="fas fa-clipboard-check me-2"></i>
                        طلب تخليص
                    </a></li>
                    <li><a class="dropdown-item text-warning" href="#" onclick="updateStatus('cancelled')">
                        <i class="fas fa-ban me-2"></i>إلغاء المعاملة
                    </a></li>
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Transaction Details -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            معلومات المعاملة
                        </h5>
                        @php
                            $statusColors = [
                                'pending' => 'bg-primary text-white',
                                'under_review' => 'bg-warning text-dark',
                                'completed' => 'bg-success text-white',
                                'clearance_requested' => 'bg-warning text-dark',
                                'clearance_approved' => 'bg-info text-white',
                                'cancelled' => 'bg-secondary text-white',
                            ];
                            
                            $statusLabels = [
                                'pending' => 'في انتظار المراجعة',
                                'under_review' => 'قيد المراجعة',
                                'completed' => 'مكتملة',
                                'clearance_requested' => 'طلب تخليص',
                                'clearance_approved' => 'تم الموافقة على التخليص',
                                'cancelled' => 'ملغية',
                            ];
                        @endphp
                        <span class="badge {{ $statusColors[$transaction->status] ?? 'bg-secondary' }} fs-6">
                            {{ $statusLabels[$transaction->status] ?? $transaction->status }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم المعاملة</label>
                            <div class="fw-bold">{{ $transaction->transaction_number }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">نوع المعاملة</label>
                            <div class="fw-bold">{{ $transaction->type_ar }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">البلدية/الأمانة</label>
                            <div class="fw-bold">{{ $transaction->municipality_ar }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الأولوية</label>
                            <div>
                                @php
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
                                @endphp
                                <span class="fw-bold {{ $priorityClasses[$transaction->priority] ?? 'text-secondary' }}">
                                    <i class="fas fa-circle me-1"></i>
                                    {{ $priorityTexts[$transaction->priority] ?? $transaction->priority }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">وصف المعاملة</label>
                            <div class="border rounded p-3 bg-light">
                                {{ $transaction->description_ar }}
                            </div>
                        </div>
                        @if($transaction->notes)
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">ملاحظات المستخدم</label>
                                <div class="border rounded p-3 bg-light">
                                    {{ $transaction->notes }}
                                </div>
                            </div>
                        @endif
                        @if($transaction->admin_notes && auth()->user()->hasRole('admin'))
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">ملاحظات الإدارة</label>
                                <div class="border rounded p-3 bg-warning bg-opacity-10">
                                    {{ $transaction->admin_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attachments -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-paperclip me-2"></i>
                        المرفقات والمستندات
                        <span class="badge bg-primary ms-2">{{ $transaction->attachments->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($transaction->attachments->count() > 0)
                        <div class="row">
                            @foreach($transaction->attachments as $attachment)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    @if(str_starts_with($attachment->mime_type, 'image/'))
                                                        <i class="fas fa-file-image fa-2x text-primary"></i>
                                                    @elseif($attachment->mime_type == 'application/pdf')
                                                        <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                    @elseif(str_contains($attachment->mime_type, 'word'))
                                                        <i class="fas fa-file-word fa-2x text-primary"></i>
                                                    @else
                                                        <i class="fas fa-file fa-2x text-secondary"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">{{ $attachment->original_name }}</h6>
                                                    <p class="card-text small text-muted mb-2">
                                                        {{ number_format($attachment->file_size / 1024 / 1024, 2) }} ميجابايت
                                                    </p>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ Storage::url($attachment->file_path) }}" 
                                                           class="btn btn-outline-primary" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ Storage::url($attachment->file_path) }}" 
                                                           class="btn btn-outline-secondary" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        @if(($transaction->user_id == auth()->id() && $transaction->status == 'pending') || auth()->user()->hasRole('admin'))
                                                            <button type="button" class="btn btn-outline-danger" 
                                                                    onclick="deleteAttachment({{ $attachment->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if($attachment->description_ar)
                                                <div class="mt-2">
                                                    <small class="text-muted">{{ $attachment->description_ar }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-paperclip fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">لا توجد مرفقات</h6>
                            <p class="text-muted">لم يتم إرفاق أي مستندات مع هذه المعاملة</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline/History -->
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        سجل المعاملة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Transaction Created -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">تم إنشاء المعاملة</h6>
                                <p class="timeline-text text-muted">
                                    تم إنشاء المعاملة بواسطة {{ $transaction->user->display_name }}
                                </p>
                                <small class="timeline-time text-muted">
                                    {{ $transaction->created_at->format('Y/m/d H:i') }}
                                </small>
                            </div>
                        </div>

                        @if($transaction->status == 'under_review' || $transaction->status == 'completed' || $transaction->status == 'clearance_requested')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">بدء المراجعة</h6>
                                    <p class="timeline-text text-muted">
                                        تم بدء مراجعة المعاملة
                                        @if($transaction->assignedTo)
                                            بواسطة {{ $transaction->assignedTo->display_name }}
                                        @endif
                                    </p>
                                    <small class="timeline-time text-muted">
                                        {{ $transaction->last_updated_by_admin_at?->format('Y/m/d H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif

                        @if($transaction->status == 'completed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">تم إنجاز المعاملة</h6>
                                    <p class="timeline-text text-muted">
                                        تم إنجاز المعاملة بنجاح
                                    </p>
                                    <small class="timeline-time text-muted">
                                        {{ $transaction->actual_completion_date?->format('Y/m/d H:i') }}
                                    </small>
                                </div>
                            </div>
                        @elseif($transaction->status == 'clearance_requested')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">طلب تخليص</h6>
                                    <p class="timeline-text text-muted">
                                        تم طلب تخليص للمعاملة
                                    </p>
                                    @if($transaction->latestClearanceRequest)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <strong>ملاحظات التخليص:</strong>
                                            {{ $transaction->latestClearanceRequest->feedback_ar }}
                                        </div>
                                        
                                        {{-- Show user response if exists --}}
                                        @if($transaction->latestClearanceRequest->status !== 'pending')
                                            <div class="mt-2 p-2 {{ $transaction->latestClearanceRequest->status === 'approved' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 rounded">
                                                <strong>رد المستخدم:</strong>
                                                <span class="badge {{ $transaction->latestClearanceRequest->status === 'approved' ? 'bg-success' : 'bg-danger' }} me-2">
                                                    {{ $transaction->latestClearanceRequest->status === 'approved' ? 'موافق' : 'مرفوض' }}
                                                </span>
                                                @if($transaction->latestClearanceRequest->user_response)
                                                    <br><small>{{ $transaction->latestClearanceRequest->user_response }}</small>
                                                @endif
                                                <br><small class="text-muted">{{ $transaction->latestClearanceRequest->responded_at?->format('Y/m/d H:i') }}</small>
                                            </div>
                                        @endif
                                    @endif
                                    <small class="timeline-time text-muted">
                                        {{ $transaction->last_updated_by_admin_at?->format('Y/m/d H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Info -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        معلومات المستخدم
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            {{ substr($transaction->user->display_name, 0, 2) }}
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $transaction->user->display_name }}</h6>
                            <p class="text-muted mb-0">{{ $transaction->user->email }}</p>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="mb-0">{{ $transaction->user->transactions()->count() }}</h5>
                                <small class="text-muted">إجمالي المعاملات</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">{{ $transaction->user->transactions()->where('status', 'completed')->count() }}</h5>
                            <small class="text-muted">المعاملات المكتملة</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Stats -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات المعاملة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>تاريخ الإنشاء</span>
                            <span class="fw-bold">{{ $transaction->created_at->format('Y/m/d') }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>التاريخ المتوقع للإنجاز</span>
                            <span class="fw-bold">{{ $transaction->expected_completion_date?->format('Y/m/d') }}</span>
                        </div>
                    </div>
                    @if($transaction->actual_completion_date)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>تاريخ الإنجاز الفعلي</span>
                                <span class="fw-bold text-success">{{ $transaction->actual_completion_date->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>عدد المرفقات</span>
                            <span class="fw-bold">{{ $transaction->attachments->count() }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>آخر تحديث</span>
                            <span class="fw-bold">{{ $transaction->updated_at->format('Y/m/d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            {{-- Temporarily removed role check --}}
            @if(true)
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            إجراءات سريعة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            {{-- Admin-only buttons --}}
                            @if(auth()->user()->email === 'admin@mowthook.sa')
                                @if($transaction->status == 'pending')
                                    <button type="button" class="btn btn-info" onclick="updateStatus('under_review')">
                                        <i class="fas fa-eye me-1"></i>
                                        بدء المراجعة
                                    </button>
                                @endif
                                @if($transaction->status != 'completed')
                                    <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                                        <i class="fas fa-check me-1"></i>
                                        إنجاز المعاملة
                                    </button>
                                @endif
                                @if($transaction->status != 'clearance_requested' && $transaction->status != 'completed')
                                    <button type="button" class="btn btn-danger" onclick="showClearanceModal()">
                                        <i class="fas fa-clipboard-check me-1"></i>
                                        طلب تخليص
                                    </button>
                                @endif
                            @endif
                            
                            {{-- User buttons --}}
                            @if($transaction->status == 'clearance_requested' && $transaction->user_id === auth()->id())
                                <a href="{{ route('clearance-requests.user-response', $transaction->latestClearanceRequest) }}" class="btn btn-warning">
                                    <i class="fas fa-reply me-1"></i>
                                    الرد على طلب التخليص
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Clearance Request Modal -->
<div class="modal fade" id="clearanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('transactions.updateStatus', $transaction) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="clearance_requested">
                
                <div class="modal-header">
                    <h5 class="modal-title">طلب تخليص المعاملة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        سيتم إرسال طلب التخليص للمستخدم مع الملاحظات المطلوبة
                    </div>
                    <div class="mb-3">
                        <label for="clearance_feedback" class="form-label">ملاحظات وتعليقات التخليص <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="clearance_feedback" name="clearance_feedback" 
                                  rows="4" placeholder="اكتب الملاحظات والتعليقات المطلوبة للتخليص..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes_clearance" class="form-label">ملاحظات إدارية (اختياري)</label>
                        <textarea class="form-control" id="admin_notes_clearance" name="admin_notes" 
                                  rows="2" placeholder="ملاحظات داخلية للإدارة..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">إرسال طلب التخليص</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-right: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    right: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    right: -23px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-right: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
}

.timeline-time {
    font-size: 12px;
}

.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 24px;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
function updateStatus(status) {
    if (confirm('هل أنت متأكد من تغيير حالة المعاملة؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("transactions.updateStatus", $transaction) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
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

function showClearanceModal() {
    const modal = new bootstrap.Modal(document.getElementById('clearanceModal'));
    modal.show();
}

function deleteAttachment(attachmentId) {
    if (confirm('هل أنت متأكد من حذف هذا الملف؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/attachments/${attachmentId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

