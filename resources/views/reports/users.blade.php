@extends('layouts.dashboard')

@section('title', 'تقرير المستخدمين')

@section('page-header')
<div>
    <h1 class="h2 mb-0">تقرير المستخدمين</h1>
    <p class="text-muted mb-0">تقرير تفصيلي لجميع المستخدمين في النظام</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
        <a href="{{ route('reports.export', 'users') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
           class="btn btn-success">
            <i class="fas fa-download me-1"></i>
            تصدير Excel
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card dashboard-card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                فلاتر البحث
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.users') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="user_type" class="form-label">نوع المستخدم</label>
                    <select class="form-select" id="user_type" name="user_type">
                        <option value="">جميع الأنواع</option>
                        @foreach($userTypes as $userType)
                        <option value="{{ $userType }}" {{ request('user_type') == $userType ? 'selected' : '' }}>
                            {{ $userType }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label">الدور</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">جميع الأدوار</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('reports.users') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card dashboard-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    المستخدمين ({{ $users->total() }})
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>المستخدم</th>
                            <th>نوع المستخدم</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>عدد المعاملات</th>
                            <th>تاريخ التسجيل</th>
                            <th>آخر تسجيل دخول</th>
                            <th>معلومات إضافية</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                                             class="rounded-circle" width="40" height="40" alt="Avatar">
                                        @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 16px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                        @if($user->phone)
                                        <div class="text-muted small">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $user->user_type }}</span>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->is_active)
                                <span class="badge bg-success">نشط</span>
                                @else
                                <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="badge bg-primary fs-6">{{ $user->transactions_count }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="small">{{ $user->created_at->format('Y-m-d') }}</div>
                                <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($user->last_login_at)
                                <div class="small">{{ $user->last_login_at->format('Y-m-d H:i') }}</div>
                                <div class="text-muted small">{{ $user->last_login_at->diffForHumans() }}</div>
                                @else
                                <span class="text-muted">لم يسجل دخول</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    @if($user->national_id)
                                    <div><strong>الهوية:</strong> {{ $user->national_id }}</div>
                                    @endif
                                    @if($user->company_name)
                                    <div><strong>الشركة:</strong> {{ $user->company_name }}</div>
                                    @endif
                                    @if($user->commercial_register)
                                    <div><strong>السجل التجاري:</strong> {{ $user->commercial_register }}</div>
                                    @endif
                                    @if($user->email_verified_at)
                                    <div class="text-success"><i class="fas fa-check-circle me-1"></i>البريد مؤكد</div>
                                    @else
                                    <div class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>البريد غير مؤكد</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($user->transactions_count > 0)
                                    <a href="{{ route('transactions.index', ['user_id' => $user->id]) }}" 
                                       class="btn btn-sm btn-outline-info" title="المعاملات">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>لا توجد مستخدمين يطابقون معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $users->total() }}</h5>
                    <p class="card-text">إجمالي المستخدمين</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $users->where('is_active', true)->count() }}</h5>
                    <p class="card-text">المستخدمين النشطين</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">{{ $users->sum('transactions_count') }}</h5>
                    <p class="card-text">إجمالي المعاملات</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $users->where('email_verified_at', null)->count() }}</h5>
                    <p class="card-text">بريد غير مؤكد</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, .card-header .d-flex > div:last-child, .pagination {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>
@endpush

