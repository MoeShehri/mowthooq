@extends('layouts.dashboard')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-edit fa-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">تعديل الملف الشخصي</h4>
                            <p class="text-muted mb-0">قم بتحديث معلوماتك الشخصية</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>يرجى تصحيح الأخطاء التالية:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    المعلومات الأساسية
                                </h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name_ar" class="form-label">الاسم بالعربية</label>
                                <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                       id="name_ar" name="name_ar" value="{{ old('name_ar', $user->name_ar) }}">
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label">رقم الهوية <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                       id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}" required>
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city_ar" class="form-label">المدينة</label>
                                <input type="text" class="form-control @error('city_ar') is-invalid @enderror" 
                                       id="city_ar" name="city_ar" value="{{ old('city_ar', $user->city_ar) }}">
                                @error('city_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address_ar" class="form-label">العنوان</label>
                                <textarea class="form-control @error('address_ar') is-invalid @enderror" 
                                          id="address_ar" name="address_ar" rows="3">{{ old('address_ar', $user->address_ar) }}</textarea>
                                @error('address_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Company Information (if applicable) -->
                        @if($user->user_type === 'office' || $user->user_type === 'developer')
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-building me-2"></i>
                                    معلومات الشركة
                                </h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_name_ar" class="form-label">اسم الشركة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name_ar') is-invalid @enderror" 
                                       id="company_name_ar" name="company_name_ar" value="{{ old('company_name_ar', $user->company_name_ar) }}" required>
                                @error('company_name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="commercial_register" class="form-label">السجل التجاري <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('commercial_register') is-invalid @enderror" 
                                       id="commercial_register" name="commercial_register" value="{{ old('commercial_register', $user->commercial_register) }}" required>
                                @error('commercial_register')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success alerts after 5 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 5000);
    }
});
</script>
@endsection

