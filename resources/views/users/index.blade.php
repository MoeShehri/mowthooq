@extends('layouts.dashboard')

@section('title', 'إدارة المستخدمين')

@section('page-header')
<div>
    <h1 class="h2 mb-0">إدارة المستخدمين</h1>
    <p class="text-muted mb-0">عرض وإدارة جميع المستخدمين في النظام</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        @can('create-users')
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إضافة مستخدم جديد
        </a>
        @endcan
        <button type="button" class="btn btn-outline-secondary" onclick="exportUsers()">
            <i class="fas fa-download me-1"></i>
            تصدير البيانات
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">البحث</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="اسم، إيميل، هاتف، أو رقم الهوية">
                </div>
                <div class="col-md-2">
                    <label for="user_type" class="form-label">نوع المستخدم</label>
                    <select class="form-select" id="user_type" name="user_type">
                        <option value="">جميع الأنواع</option>
                        <option value="أفراد" {{ request('user_type') == 'أفراد' ? 'selected' : '' }}>أفراد</option>
                        <option value="مكاتب هندسية" {{ request('user_type') == 'مكاتب هندسية' ? 'selected' : '' }}>مكاتب هندسية</option>
                        <option value="مطورين عقاريين" {{ request('user_type') == 'مطورين عقاريين' ? 'selected' : '' }}>مطورين عقاريين</option>
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
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            إلغاء
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
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    قائمة المستخدمين ({{ $users->total() }})
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTable()">
                        <i class="fas fa-sync-alt"></i>
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
                            <th>تاريخ التسجيل</th>
                            <th>آخر نشاط</th>
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('edit-users')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('edit-users')
                                    <button type="button" class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}" 
                                            onclick="toggleUserStatus({{ $user->id }})" 
                                            title="{{ $user->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                    @endcan
                                    @can('delete-users')
                                    @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteUser({{ $user->id }})" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>لا توجد مستخدمين</p>
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
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
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

function deleteUser(userId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/users/${userId}`;
    modal.show();
}

function refreshTable() {
    window.location.reload();
}

function exportUsers() {
    // Add export functionality here
    alert('ميزة التصدير ستكون متاحة قريباً');
}
</script>
@endpush

