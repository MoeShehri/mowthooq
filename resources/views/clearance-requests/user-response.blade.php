@extends('layouts.dashboard-modern')

@section('title', 'الرد على طلب التخليص - موثوق')

@section('page-header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">الرد على طلب التخليص</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">المعاملات</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transactions.show', $clearanceRequest->transaction) }}">{{ $clearanceRequest->transaction->transaction_number }}</a></li>
                <li class="breadcrumb-item active">الرد على طلب التخليص</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Clearance Request Details -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        تفاصيل طلب التخليص
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رقم المعاملة</label>
                            <p class="text-muted">{{ $clearanceRequest->transaction->transaction_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نوع المعاملة</label>
                            <p class="text-muted">{{ $clearanceRequest->transaction->type_ar }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">تاريخ الطلب</label>
                            <p class="text-muted">{{ $clearanceRequest->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">طلب من</label>
                            <p class="text-muted">{{ $clearanceRequest->requestedBy->display_name }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label fw-bold">ملاحظات طلب التخليص</label>
                        <div class="p-3 bg-light rounded">
                            {{ $clearanceRequest->feedback_ar }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Form -->
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-reply me-2"></i>
                        ردك على طلب التخليص
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('clearance-requests.respond', $clearanceRequest) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">اختر ردك</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="response" id="approve" value="approved" required>
                                                <label class="form-check-label w-100" for="approve">
                                                    <i class="fas fa-check-circle text-success fa-3x mb-2"></i>
                                                    <h5 class="text-success">موافق على التخليص</h5>
                                                    <p class="text-muted small">أوافق على طلب التخليص وأريد المتابعة</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="response" id="reject" value="rejected" required>
                                                <label class="form-check-label w-100" for="reject">
                                                    <i class="fas fa-times-circle text-danger fa-3x mb-2"></i>
                                                    <h5 class="text-danger">رفض التخليص</h5>
                                                    <p class="text-muted small">لا أوافق على طلب التخليص</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="user_response" class="form-label fw-bold">ملاحظاتك (اختياري)</label>
                            <textarea class="form-control" id="user_response" name="user_response" rows="4" 
                                placeholder="اكتب أي ملاحظات أو توضيحات إضافية..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>
                                إرسال الرد
                            </button>
                            <a href="{{ route('transactions.show', $clearanceRequest->transaction) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i>
                                العودة للمعاملة
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Transaction Summary -->
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        ملخص المعاملة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الحالة الحالية</label>
                        <span class="badge bg-warning fs-6">طلب تخليص</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">البلدية/الأمانة</label>
                        <p class="text-muted mb-0">{{ $clearanceRequest->transaction->municipality_ar }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">الأولوية</label>
                        <p class="text-muted mb-0">{{ $clearanceRequest->transaction->priority_ar }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ الإنشاء</label>
                        <p class="text-muted mb-0">{{ $clearanceRequest->transaction->created_at->format('Y/m/d') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">التاريخ المتوقع للإنجاز</label>
                        <p class="text-muted mb-0">{{ $clearanceRequest->transaction->expected_completion_date?->format('Y/m/d') }}</p>
                    </div>
                    
                    <a href="{{ route('transactions.show', $clearanceRequest->transaction) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-eye me-1"></i>
                        عرض تفاصيل المعاملة
                    </a>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card dashboard-card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        مساعدة
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">ماذا يعني طلب التخليص؟</h6>
                    <p class="text-muted small">طلب التخليص يعني أن الموظف المختص يطلب منك توضيحات أو تعديلات قبل إنجاز المعاملة.</p>
                    
                    <h6 class="fw-bold mt-3">ماذا لو وافقت؟</h6>
                    <p class="text-muted small">ستنتقل المعاملة إلى مرحلة "تحت المراجعة" وسيتم العمل على إنجازها.</p>
                    
                    <h6 class="fw-bold mt-3">ماذا لو رفضت؟</h6>
                    <p class="text-muted small">ستعود المعاملة للموظف المختص لمراجعة إضافية بناءً على ملاحظاتك.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-check-input:checked + .form-check-label .card {
    border-width: 2px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.card.border-success:hover,
.card.border-danger:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
</style>
@endpush

