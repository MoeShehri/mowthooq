@extends('layouts.dashboard')

@section('title', 'تعديل المستخدم - ' . $user->name)

@section('page-header')
<div>
    <h1 class="h2 mb-0">تعديل المستخدم</h1>
    <p class="text-muted mb-0">{{ $user->name }}</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة لتفاصيل المستخدم
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            المعلومات الأساسية
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
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
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="05xxxxxxxx">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label">رقم الهوية</label>
                                <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                       id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}" placeholder="1xxxxxxxxx">
                                @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-lock me-2"></i>
                            تغيير كلمة المرور
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Type and Role -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>
                            نوع المستخدم والصلاحيات
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_type" class="form-label">نوع المستخدم <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_type') is-invalid @enderror" 
                                        id="user_type" name="user_type" required onchange="toggleCompanyFields()">
                                    <option value="">اختر نوع المستخدم</option>
                                    <option value="أفراد" {{ old('user_type', $user->user_type) == 'أفراد' ? 'selected' : '' }}>أفراد</option>
                                    <option value="مكاتب هندسية" {{ old('user_type', $user->user_type) == 'مكاتب هندسية' ? 'selected' : '' }}>مكاتب هندسية</option>
                                    <option value="مطورين عقاريين" {{ old('user_type', $user->user_type) == 'مطورين عقاريين' ? 'selected' : '' }}>مطورين عقاريين</option>
                                </select>
                                @error('user_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">اختر الدور</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                            {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        تفعيل الحساب
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="card dashboard-card mb-4" id="companyFields" 
                     style="display: {{ $user->user_type !== 'أفراد' ? 'block' : 'none' }};">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>
                            معلومات الشركة/المؤسسة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">اسم الشركة</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
                                @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="commercial_register" class="form-label">السجل التجاري</label>
                                <input type="text" class="form-control @error('commercial_register') is-invalid @enderror" 
                                       id="commercial_register" name="commercial_register" value="{{ old('commercial_register', $user->commercial_register) }}">
                                @error('commercial_register')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">المنصب</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position', $user->position) }}">
                                @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="license_number" class="form-label">رقم الترخيص</label>
                                <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                       id="license_number" name="license_number" value="{{ old('license_number', $user->license_number) }}">
                                @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات إضافية
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" 
                                       value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">الجنس</label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender">
                                    <option value="">اختر الجنس</option>
                                    <option value="ذكر" {{ old('gender', $user->gender) == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                                    <option value="أنثى" {{ old('gender', $user->gender) == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="avatar" class="form-label">الصورة الشخصية</label>
                                @if($user->avatar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         class="rounded" width="100" height="100" alt="Current Avatar">
                                    <div class="form-text">الصورة الحالية</div>
                                </div>
                                @endif
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" name="avatar" accept="image/*">
                                @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">الحد الأقصى لحجم الملف: 2 ميجابايت. الصيغ المدعومة: JPG, PNG</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCompanyFields() {
    const userType = document.getElementById('user_type').value;
    const companyFields = document.getElementById('companyFields');
    
    if (userType === 'أفراد') {
        companyFields.style.display = 'none';
        // Clear company fields
        document.getElementById('company_name').value = '';
        document.getElementById('commercial_register').value = '';
        document.getElementById('position').value = '';
        document.getElementById('license_number').value = '';
    } else {
        companyFields.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Don't auto-toggle on edit page, keep current state
});
</script>
@endpush

