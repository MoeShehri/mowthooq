<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار المعاملة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>تفاصيل المعاملة: {{ $transaction->transaction_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>رقم المعاملة:</strong> {{ $transaction->transaction_number }}</p>
                        <p><strong>نوع المعاملة:</strong> {{ $transaction->type_ar }}</p>
                        <p><strong>الحالة:</strong> {{ $transaction->status }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>المستخدم:</strong> {{ $transaction->user->display_name ?? 'غير محدد' }}</p>
                        <p><strong>البلدية:</strong> {{ $transaction->municipality_ar }}</p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $transaction->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <h5>الإجراءات المتاحة</h5>
                    <button type="button" class="btn btn-info" onclick="updateStatus('under_review')">
                        بدء المراجعة
                    </button>
                    <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                        إنجاز المعاملة
                    </button>
                    <button type="button" class="btn btn-warning" onclick="alert('طلب تخليص')">
                        طلب تخليص
                    </button>
                </div>

                <!-- Timeline -->
                <div class="mt-4">
                    <h5>سجل المعاملة</h5>
                    
                    @if($transaction->latestClearanceRequest)
                    <div class="alert alert-warning">
                        <h6>طلب تخليص</h6>
                        <p><strong>ملاحظات التخليص:</strong> {{ $transaction->latestClearanceRequest->feedback_ar }}</p>
                        
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
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateStatus(status) {
        if (confirm('هل أنت متأكد من تغيير حالة المعاملة؟')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/transactions/{{ $transaction->id }}/status';
            
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
    </script>
</body>
</html>

