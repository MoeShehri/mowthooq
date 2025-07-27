@extends('layouts.dashboard')

@section('title', 'تعديل المعاملة - ' . $transaction->transaction_number)

@section('page-header')
<div>
    <h1 class="h2 mb-0">تعديل المعاملة</h1>
    <p class="text-muted mb-0">{{ $transaction->transaction_number }}</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للمعاملة
        </a>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-1"></i>
            جميع المعاملات
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        تعديل بيانات المعاملة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        يمكنك تعديل المعاملة فقط عندما تكون في حالة "قيد الانتظار". بعد بدء المراجعة لن تتمكن من التعديل.
                    </div>

                    <form action="{{ route('transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data" id="transactionForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Transaction Type -->
                            <div class="col-md-6 mb-3">
                                <label for="type_ar" class="form-label">
                                    <i class="fas fa-file-alt me-1"></i>
                                    نوع المعاملة <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type_ar') is-invalid @enderror" 
                                        id="type_ar" name="type_ar" required>
                                    <option value="">اختر نوع المعاملة</option>
                                    @foreach($transactionTypes as $typeAr => $typeEn)
                                        <option value="{{ $typeAr }}" 
                                                data-type-en="{{ $typeEn }}"
                                                {{ (old('type_ar', $transaction->type_ar) == $typeAr) ? 'selected' : '' }}>
                                            {{ $typeAr }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="type_en" id="type_en" value="{{ old('type_en', $transaction->type_en) }}">
                                @error('type_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Municipality -->
                            <div class="col-md-6 mb-3">
                                <label for="municipality_ar" class="form-label">
                                    <i class="fas fa-building me-1"></i>
                                    البلدية/الأمانة <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('municipality_ar') is-invalid @enderror" 
                                        id="municipality_ar" name="municipality_ar" required>
                                    <option value="">اختر البلدية</option>
                                    @foreach($municipalities as $municipality)
                                        <option value="{{ $municipality }}" 
                                                {{ (old('municipality_ar', $transaction->municipality_ar) == $municipality) ? 'selected' : '' }}>
                                            {{ $municipality }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('municipality_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description_ar" class="form-label">
                                <i class="fas fa-align-right me-1"></i>
                                وصف المعاملة <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                      id="description_ar" name="description_ar" rows="4" 
                                      placeholder="اكتب وصفاً مفصلاً للمعاملة المطلوبة..." required>{{ old('description_ar', $transaction->description_ar) }}</textarea>
                            <div class="form-text">
                                يرجى كتابة وصف واضح ومفصل للمعاملة المطلوبة لتسهيل عملية المراجعة
                            </div>
                            @error('description_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Priority -->
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    الأولوية <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">اختر الأولوية</option>
                                    <option value="low" {{ (old('priority', $transaction->priority) == 'low') ? 'selected' : '' }}>
                                        منخفضة
                                    </option>
                                    <option value="normal" {{ (old('priority', $transaction->priority) == 'normal') ? 'selected' : '' }}>
                                        عادية
                                    </option>
                                    <option value="high" {{ (old('priority', $transaction->priority) == 'high') ? 'selected' : '' }}>
                                        عالية
                                    </option>
                                    <option value="urgent" {{ (old('priority', $transaction->priority) == 'urgent') ? 'selected' : '' }}>
                                        عاجلة
                                    </option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    ملاحظات إضافية
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="أي ملاحظات إضافية...">{{ old('notes', $transaction->notes) }}</textarea>
                            </div>
                        </div>

                        <!-- Current Attachments -->
                        @if($transaction->attachments->count() > 0)
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-paperclip me-1"></i>
                                    المرفقات الحالية
                                </label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="row">
                                        @foreach($transaction->attachments as $attachment)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center">
                                                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                                                <i class="fas fa-file-image fa-2x me-2 text-primary"></i>
                                                            @elseif($attachment->mime_type == 'application/pdf')
                                                                <i class="fas fa-file-pdf fa-2x me-2 text-danger"></i>
                                                            @elseif(str_contains($attachment->mime_type, 'word'))
                                                                <i class="fas fa-file-word fa-2x me-2 text-primary"></i>
                                                            @else
                                                                <i class="fas fa-file fa-2x me-2 text-secondary"></i>
                                                            @endif
                                                            <div class="flex-grow-1">
                                                                <h6 class="card-title mb-1 small">{{ $attachment->original_name }}</h6>
                                                                <p class="card-text small text-muted mb-0">
                                                                    {{ number_format($attachment->file_size / 1024 / 1024, 2) }} ميجابايت
                                                                </p>
                                                                <div class="btn-group btn-group-sm mt-1">
                                                                    <a href="{{ Storage::url($attachment->file_path) }}" 
                                                                       class="btn btn-outline-primary btn-sm" target="_blank">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                            onclick="deleteAttachment({{ $attachment->id }})">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Add New Attachments -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-plus me-1"></i>
                                إضافة مرفقات جديدة
                            </label>
                            <div class="border rounded p-3 bg-light">
                                <div class="mb-3">
                                    <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        الأنواع المدعومة: PDF, DOC, DOCX, JPG, JPEG, PNG | الحد الأقصى: 10 ميجابايت لكل ملف
                                    </div>
                                    @error('attachments.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- File Preview Area -->
                                <div id="filePreview" class="mt-3" style="display: none;">
                                    <h6 class="text-muted">الملفات الجديدة المحددة:</h6>
                                    <div id="fileList" class="row"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle transaction type selection
    const typeSelect = document.getElementById('type_ar');
    const typeEnInput = document.getElementById('type_en');
    
    // Set initial value
    const selectedOption = typeSelect.options[typeSelect.selectedIndex];
    if (selectedOption) {
        typeEnInput.value = selectedOption.dataset.typeEn || '';
    }
    
    typeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        typeEnInput.value = selectedOption.dataset.typeEn || '';
    });

    // Handle file selection and preview
    const fileInput = document.getElementById('attachments');
    const filePreview = document.getElementById('filePreview');
    const fileList = document.getElementById('fileList');

    fileInput.addEventListener('change', function() {
        const files = Array.from(this.files);
        
        if (files.length > 0) {
            filePreview.style.display = 'block';
            fileList.innerHTML = '';
            
            files.forEach((file, index) => {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileIcon = getFileIcon(file.type);
                
                const fileCard = document.createElement('div');
                fileCard.className = 'col-md-6 col-lg-4 mb-2';
                fileCard.innerHTML = `
                    <div class="card border">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <i class="${fileIcon} fa-2x me-2 text-primary"></i>
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1 small">${file.name}</h6>
                                    <p class="card-text small text-muted mb-0">${fileSize} ميجابايت</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                fileList.appendChild(fileCard);
            });
        } else {
            filePreview.style.display = 'none';
        }
    });

    // Form submission handling
    const form = document.getElementById('transactionForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الحفظ...';
    });

    // File icon helper function
    function getFileIcon(mimeType) {
        if (mimeType.includes('pdf')) return 'fas fa-file-pdf';
        if (mimeType.includes('word') || mimeType.includes('document')) return 'fas fa-file-word';
        if (mimeType.includes('image')) return 'fas fa-file-image';
        return 'fas fa-file';
    }

    // Validate file sizes
    fileInput.addEventListener('change', function() {
        const files = Array.from(this.files);
        const maxSize = 10 * 1024 * 1024; // 10MB
        let hasError = false;
        
        files.forEach(file => {
            if (file.size > maxSize) {
                hasError = true;
                alert(`الملف "${file.name}" كبير جداً. الحد الأقصى 10 ميجابايت.`);
            }
        });
        
        if (hasError) {
            this.value = '';
            filePreview.style.display = 'none';
        }
    });
});

function deleteAttachment(attachmentId) {
    if (confirm('هل أنت متأكد من حذف هذا الملف؟ لا يمكن التراجع عن هذا الإجراء.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/transactions/attachments/${attachmentId}`;
        
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

