@extends('layouts.dashboard')

@section('title', 'تقرير المعاملات')

@section('page-header')
<div>
    <h1 class="h2 mb-0">تقرير المعاملات</h1>
    <p class="text-muted mb-0">تقرير تفصيلي لجميع المعاملات في النظام</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
        <a href="{{ route('reports.export', 'transactions') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
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
            <form method="GET" action="{{ route('reports.transactions') }}" class="row g-3">
                <div class="col-md-2">
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            @switch($status)
                                @case('pending') قيد الانتظار @break
                                @case('under_review') قيد المراجعة @break
                                @case('completed') مكتملة @break
                                @case('rejected') مرفوضة @break
                                @case('cancelled') ملغاة @break
                                @default {{ $status }}
                            @endswitch
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">نوع المعاملة</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">جميع الأنواع</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="municipality" class="form-label">البلدية</label>
                    <select class="form-select" id="municipality" name="municipality">
                        <option value="">جميع البلديات</option>
                        @foreach($municipalities as $municipality)
                        <option value="{{ $municipality }}" {{ request('municipality') == $municipality ? 'selected' : '' }}>
                            {{ $municipality }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
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
                    <label for="start_date" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('reports.transactions') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            إلغاء الفلاتر
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card dashboard-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    المعاملات ({{ $transactions->total() }})
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
                            <th>رقم المعاملة</th>
                            <th>نوع المعاملة</th>
                            <th>البلدية</th>
                            <th>المستخدم</th>
                            <th>نوع المستخدم</th>
                            <th>الحالة</th>
                            <th>الأولوية</th>
                            <th>تاريخ الإنشاء</th>
                            <th>آخر تحديث</th>
                            <th>المسؤول</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('transactions.show', $transaction) }}" class="text-decoration-none fw-bold">
                                    {{ $transaction->transaction_number }}
                                </a>
                            </td>
                            <td>{{ $transaction->type_ar }}</td>
                            <td>{{ $transaction->municipality_ar }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        @if($transaction->user->avatar)
                                        <img src="{{ asset('storage/' . $transaction->user->avatar) }}" 
                                             class="rounded-circle" width="30" height="30" alt="Avatar">
                                        @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ substr($transaction->user->name, 0, 1) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $transaction->user->name }}</div>
                                        <div class="text-muted small">{{ $transaction->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $transaction->user->user_type }}</span>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                @php
                                    $priorityClasses = [
                                        'low' => 'bg-light text-dark',
                                        'normal' => 'bg-primary',
                                        'high' => 'bg-warning text-dark',
                                        'urgent' => 'bg-danger'
                                    ];
                                    $priorityTexts = [
                                        'low' => 'منخفضة',
                                        'normal' => 'عادية',
                                        'high' => 'عالية',
                                        'urgent' => 'عاجلة'
                                    ];
                                @endphp
                                <span class="badge {{ $priorityClasses[$transaction->priority] ?? 'bg-primary' }}">
                                    {{ $priorityTexts[$transaction->priority] ?? $transaction->priority }}
                                </span>
                            </td>
                            <td>
                                <div class="small">{{ $transaction->created_at->format('Y-m-d') }}</div>
                                <div class="text-muted small">{{ $transaction->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="small">{{ $transaction->updated_at->format('Y-m-d') }}</div>
                                <div class="text-muted small">{{ $transaction->updated_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($transaction->assignedTo)
                                <div class="small fw-bold">{{ $transaction->assignedTo->name }}</div>
                                @else
                                <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('transactions.show', $transaction) }}" 
                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($transaction->attachments->count() > 0)
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            title="المرفقات ({{ $transaction->attachments->count() }})">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <p>لا توجد معاملات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
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

