@extends('layouts.dashboard')

@section('title', 'إكمال الملف الشخصي')

@section('content')
<div class="fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="modern-card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-primary">مرحباً {{ $user->first_name }}!</h3>
                        <p class="text-muted">يرجى إكمال معلوماتك الأساسية للمتابعة</p>
                    </div>

                    <form method="POST" action="{{ route('profile.complete.store') }}">
                        @csrf

                        <!-- Essential Information Only -->
                        <div class="text-start">
                            <div class="mb-3">
                                <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       placeholder="05xxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($user->user_type === 'office' || $user->user_type === 'developer')
                            <!-- Company Information (Simplified) -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>معلومات إضافية مطلوبة:</strong> نظراً لأنك مسجل كـ
                                @if($user->user_type === 'office')
                                    "مكتب هندسي"
                                @else
                                    "مطور عقاري"
                                @endif
                                ، يرجى إدخال المعلومات التالية:
                            </div>

                            <div class="mb-3">
                                <label for="company_name_ar" class="form-label">
                                    @if($user->user_type === 'office')
                                        اسم المكتب الهندسي
                                    @else
                                        اسم الشركة
                                    @endif
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('company_name_ar') is-invalid @enderror" 
                                       id="company_name_ar" name="company_name_ar" value="{{ old('company_name_ar', $user->company_name_ar) }}" required>
                                @error('company_name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="commercial_register" class="form-label">السجل التجاري <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('commercial_register') is-invalid @enderror" 
                                       id="commercial_register" name="commercial_register" value="{{ old('commercial_register', $user->commercial_register) }}" 
                                       placeholder="رقم السجل التجاري" required>
                                @error('commercial_register')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Optional Fields -->
                            <div class="mb-3">
                                <label for="city_ar" class="form-label">المدينة (اختياري)</label>
                                <select class="form-select @error('city_ar') is-invalid @enderror" id="city_ar" name="city_ar">
                                    <option value="">اختر المدينة</option>
                                    <option value="الرياض" {{ old('city_ar', $user->city_ar) == 'الرياض' ? 'selected' : '' }}>الرياض</option>
                                    <option value="جدة" {{ old('city_ar', $user->city_ar) == 'جدة' ? 'selected' : '' }}>جدة</option>
                                    <option value="مكة المكرمة" {{ old('city_ar', $user->city_ar) == 'مكة المكرمة' ? 'selected' : '' }}>مكة المكرمة</option>
                                    <option value="المدينة المنورة" {{ old('city_ar', $user->city_ar) == 'المدينة المنورة' ? 'selected' : '' }}>المدينة المنورة</option>
                                    <option value="الدمام" {{ old('city_ar', $user->city_ar) == 'الدمام' ? 'selected' : '' }}>الدمام</option>
                                    <option value="الخبر" {{ old('city_ar', $user->city_ar) == 'الخبر' ? 'selected' : '' }}>الخبر</option>
                                    <option value="الطائف" {{ old('city_ar', $user->city_ar) == 'الطائف' ? 'selected' : '' }}>الطائف</option>
                                    <option value="بريدة" {{ old('city_ar', $user->city_ar) == 'بريدة' ? 'selected' : '' }}>بريدة</option>
                                    <option value="تبوك" {{ old('city_ar', $user->city_ar) == 'تبوك' ? 'selected' : '' }}>تبوك</option>
                                    <option value="خميس مشيط" {{ old('city_ar', $user->city_ar) == 'خميس مشيط' ? 'selected' : '' }}>خميس مشيط</option>
                                </select>
                                @error('city_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="address_ar" class="form-label">العنوان (اختياري)</label>
                                <textarea class="form-control @error('address_ar') is-invalid @enderror" 
                                          id="address_ar" name="address_ar" rows="2" 
                                          placeholder="العنوان التفصيلي">{{ old('address_ar', $user->address_ar) }}</textarea>
                                @error('address_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                        @if($user->user_type === 'office' || $user->user_type === 'developer')
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>معلومات الشركة:</strong> مطلوبة للتحقق من صحة البيانات
                        </li>
                        @endif
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
@endsection

@push('scripts')
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
@endpush

