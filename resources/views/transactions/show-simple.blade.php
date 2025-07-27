@extends('layouts.app')

@section('title', 'تفاصيل المعاملة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>تفاصيل المعاملة: {{ $transaction->transaction_number }}</h4>
                </div>
                <div class="card-body">
                    <!-- Transaction Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>رقم المعاملة:</strong> {{ $transaction->transaction_number }}</p>
                            <p><strong>نوع المعاملة:</strong> {{ $transaction->type }}</p>
                            <p><strong>الحالة:</strong> 
                                <span class="badge 
                                    @if($transaction->status == 'pending') bg-warning
                                    @elseif($transaction->status == 'under_review') bg-info
                                    @elseif($transaction->status == 'completed') bg-success
                                    @elseif($transaction->status == 'clearance_requested') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ $transaction->status_ar }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>المستخدم:</strong> {{ $transaction->user->display_name }}</p>
                            <p><strong>البلدية:</strong> {{ $transaction->municipality }}</p>
                            <p><strong>تاريخ الإنشاء:</strong> {{ $transaction->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if(auth()->user()->email === 'admin@mowthook.sa')
                    <div class="mb-4">
                        <h5>الإجراءات المتاحة</h5>
                        <div class="btn-group" role="group">
                            @if($transaction->status == 'pending')
                                <button type="button" class="btn btn-info" onclick="updateStatus('under_review')">
                                    بدء المراجعة
                                </button>
                            @endif
                            @if($transaction->status != 'completed')
                                <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                                    إنجاز المعاملة
                                </button>
                            @endif
                            @if($transaction->status != 'clearance_requested' && $transaction->status != 'completed')
                                <button type="button" class="btn btn-warning" onclick="showClearanceModal()">
                                    طلب تخليص
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Timeline -->
                    <div class="mb-4">
                        <h5>سجل المعاملة</h5>
                        <div class="timeline">
                            <!-- Created -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>تم إنشاء المعاملة</h6>
                                    <p>تم إنشاء المعاملة بواسطة {{ $transaction->user->display_name }}</p>
                                    <small>{{ $transaction->created_at->format('Y/m/d H:i') }}</small>
                                </div>
                            </div>

                            <!-- Under Review -->
                            @if($transaction->status == 'under_review' || $transaction->status == 'completed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6>بدء المراجعة</h6>
                                    <p>تم بدء مراجعة المعاملة</p>
                                    <small>{{ $transaction->last_updated_by_admin_at?->format('Y/m/d H:i') }}</small>
                                </div>
                            </div>
                            @endif

                            <!-- Clearance Request -->
                            @if($transaction->status == 'clearance_requested')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6>طلب تخليص</h6>
                                    <p>تم طلب تخليص للمعاملة</p>
                                    @if($transaction->latestClearanceRequest)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <strong>ملاحظات التخليص:</strong><br>
                                            {{ $transaction->latestClearanceRequest->feedback_ar }}
                                        </div>
                                        
                                        @if($transaction->latestClearanceRequest->status !== 'pending')
                                            <div class="mt-2 p-2 {{ $transaction->latestClearanceRequest->status === 'approved' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 rounded">
                                                <strong>رد المستخدم:</strong>
                                                <span class="badge {{ $transaction->latestClearanceRequest->status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $transaction->latestClearanceRequest->status === 'approved' ? 'موافق' : 'مرفوض' }}
                                                </span><br>
                                                @if($transaction->latestClearanceRequest->user_response)
                                                    <strong>تعليق المستخدم:</strong> {{ $transaction->latestClearanceRequest->user_response }}<br>
                                                @endif
                                                <small>{{ $transaction->latestClearanceRequest->responded_at?->format('Y/m/d H:i') }}</small>
                                            </div>
                                        @endif
                                    @endif
                                    <small>{{ $transaction->last_updated_by_admin_at?->format('Y/m/d H:i') }}</small>
                                </div>
                            </div>
                            @endif

                            <!-- Completed -->
                            @if($transaction->status == 'completed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>تم إنجاز المعاملة</h6>
                                    <p>تم إنجاز المعاملة بنجاح</p>
                                    <small>{{ $transaction->actual_completion_date?->format('Y/m/d H:i') }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="mb-3">
                        <label for="clearance_feedback" class="form-label">ملاحظات التخليص <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="clearance_feedback" name="clearance_feedback" 
                                  rows="4" placeholder="اكتب الملاحظات المطلوبة للتخليص..." required></textarea>
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
</script>
@endpush

