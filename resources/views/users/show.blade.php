@extends('layouts.dashboard')

@section('title', 'تفاصيل المستخدم - ' . $user->name)

@section('page-header')
<div>
    <h1 class="h2 mb-0">تفاصيل المستخدم</h1>
    <p class="text-muted mb-0">{{ $user->name }}</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للمستخدمين
        </a>
        @can('edit-users')
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>
            تعديل
        </a>
        @endcan
        @can('edit-users')
        <button type="button" class="btn btn-{{ $user->is_active ? 'danger' : 'success' }}" 
                onclick="toggleUserStatus({{ $user->id }})">
            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-1"></i>
            {{ $user->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
        </button>
        @endcan
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- User Profile -->
        <div class="col-lg-4">
            <div class="card dashboard-card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                             class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                        @else
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 120px; height: 120px; font-size: 48px;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="mb-3">
                        <span class="badge bg-info fs-6">{{ $user->user_type }}</span>
                        @if($user->is_active)
                        <span class="badge bg-success fs-6">نشط</span>
                        @else
                        <span class="badge bg-danger fs-6">غير نشط</span>
                        @endif
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <div class="fw-bold">{{ $user->transactions->count() }}</div>
                            <div class="text-muted small">المعاملات</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $user->notifications->count() }}</div>
                            <div class="text-muted small">الإشعارات</div>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $user->created_at->diffInDays() }}</div>
                            <div class="text-muted small">يوم في النظام</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        الأدوار والصلاحيات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">الأدوار</label>
                        <div>
                            @forelse($user->roles as $role)
                            <span class="badge bg-secondary me-1 mb-1">{{ $role->name }}</span>
                            @empty
                            <span class="text-muted">لا توجد أدوار</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-muted">الصلاحيات</label>
                        <div>
                            @forelse($user->getAllPermissions() as $permission)
                            <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                            @empty
                            <span class="text-muted">لا توجد صلاحيات</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        المعلومات الشخصية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الاسم الكامل</label>
                            <div class="fw-bold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">البريد الإلكتروني</label>
                            <div class="fw-bold">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الهاتف</label>
                            <div class="fw-bold">{{ $user->phone ?: 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الهوية</label>
                            <div class="fw-bold">{{ $user->national_id ?: 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تاريخ الميلاد</label>
                            <div class="fw-bold">{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الجنس</label>
                            <div class="fw-bold">{{ $user->gender ?: 'غير محدد' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information (if applicable) -->
            @if($user->user_type !== 'أفراد')
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        معلومات الشركة/المؤسسة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">اسم الشركة</label>
                            <div class="fw-bold">{{ $user->company_name ?: 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">السجل التجاري</label>
                            <div class="fw-bold">{{ $user->commercial_register ?: 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">المنصب</label>
                            <div class="fw-bold">{{ $user->position ?: 'غير محدد' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الترخيص</label>
                            <div class="fw-bold">{{ $user->license_number ?: 'غير محدد' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Account Information -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        معلومات الحساب
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تاريخ التسجيل</label>
                            <div class="fw-bold">{{ $user->created_at->format('Y-m-d H:i') }}</div>
                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">آخر تسجيل دخول</label>
                            @if($user->last_login_at)
                            <div class="fw-bold">{{ $user->last_login_at->format('Y-m-d H:i') }}</div>
                            <div class="text-muted small">{{ $user->last_login_at->diffForHumans() }}</div>
                            @else
                            <div class="text-muted">لم يسجل دخول بعد</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">تأكيد البريد الإلكتروني</label>
                            @if($user->email_verified_at)
                            <div class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                مؤكد
                            </div>
                            @else
                            <div class="text-warning">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                غير مؤكد
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">حالة الحساب</label>
                            @if($user->is_active)
                            <div class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                نشط
                            </div>
                            @else
                            <div class="text-danger">
                                <i class="fas fa-ban me-1"></i>
                                غير نشط
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card dashboard-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            المعاملات الأخيرة
                        </h6>
                        <a href="{{ route('transactions.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($user->transactions()->latest()->limit(5)->get() as $transaction)
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="fw-bold">{{ $transaction->transaction_number }}</div>
                            <div class="text-muted small">{{ $transaction->type_ar }}</div>
                        </div>
                        <div class="text-end">
                            @php
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
                            @endphp
                            <span class="badge {{ $statusClasses[$transaction->status] ?? 'bg-secondary' }}">
                                {{ $statusTexts[$transaction->status] ?? $transaction->status }}
                            </span>
                            <div class="text-muted small">{{ $transaction->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <p>لا توجد معاملات</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleUserStatus(userId) {
    if (confirm('هل أنت متأكد من تغيير حالة هذا المستخدم؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}/toggle-status`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

