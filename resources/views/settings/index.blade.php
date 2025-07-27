@extends('layouts.dashboard')

@section('title', 'إعدادات النظام')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">إعدادات النظام</h2>
                    <p class="text-muted mb-0">إدارة إعدادات التطبيق والنظام</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary me-2" onclick="clearCache()">
                        <i class="fas fa-broom me-2"></i>
                        مسح الذاكرة المؤقتة
                    </button>
                    <button class="btn btn-primary" onclick="optimizeApp()">
                        <i class="fas fa-rocket me-2"></i>
                        تحسين التطبيق
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- System Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات النظام
                    </h5>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-primary text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ number_format($stats['total_users']) }}</h3>
                                    <p class="stat-label">إجمالي المستخدمين</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-success text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ number_format($stats['active_users']) }}</h3>
                                    <p class="stat-label">المستخدمين النشطين</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-info text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ number_format($stats['total_transactions']) }}</h3>
                                    <p class="stat-label">إجمالي المعاملات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-warning text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ number_format($stats['pending_transactions']) }}</h3>
                                    <p class="stat-label">معاملات قيد الانتظار</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-cog me-2"></i>
                        الإعدادات العامة
                    </h5>
                    <form method="POST" action="{{ route('settings.update-general') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="app_name" class="form-label">اسم التطبيق</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" 
                                   value="{{ config('app.name', 'موثوق') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="app_description" class="form-label">وصف التطبيق</label>
                            <textarea class="form-control" id="app_description" name="app_description" rows="3">نظام إدارة المعاملات البلدية الإلكتروني</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">البريد الإلكتروني للتواصل</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                   value="support@mowthook.sa" required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">رقم الهاتف للتواصل</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                   value="966112345678" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                <label class="form-check-label" for="maintenance_mode">
                                    وضع الصيانة
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            حفظ الإعدادات العامة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        إعدادات البريد الإلكتروني
                    </h5>
                    <form method="POST" action="{{ route('settings.update-email') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="mail_driver" class="form-label">نوع البريد الإلكتروني</label>
                            <select class="form-select" id="mail_driver" name="mail_driver" required>
                                <option value="smtp">SMTP</option>
                                <option value="sendmail">Sendmail</option>
                                <option value="mailgun">Mailgun</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mail_host" class="form-label">خادم البريد الإلكتروني</label>
                            <input type="text" class="form-control" id="mail_host" name="mail_host" 
                                   value="smtp.gmail.com" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_port" class="form-label">المنفذ</label>
                                <input type="number" class="form-control" id="mail_port" name="mail_port" 
                                       value="587" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_encryption" class="form-label">التشفير</label>
                                <select class="form-select" id="mail_encryption" name="mail_encryption">
                                    <option value="">بدون تشفير</option>
                                    <option value="tls" selected>TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mail_username" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="mail_username" name="mail_username" required>
                        </div>

                        <div class="mb-3">
                            <label for="mail_password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="mail_from_address" class="form-label">عنوان المرسل</label>
                            <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                                   value="noreply@mowthook.sa" required>
                        </div>

                        <div class="mb-3">
                            <label for="mail_from_name" class="form-label">اسم المرسل</label>
                            <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" 
                                   value="موثوق" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            حفظ إعدادات البريد
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات النظام
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">إصدار PHP:</td>
                                    <td>{{ $systemInfo['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">إصدار Laravel:</td>
                                    <td>{{ $systemInfo['laravel_version'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">وقت الخادم:</td>
                                    <td>{{ $systemInfo['server_time'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">المنطقة الزمنية:</td>
                                    <td>{{ $systemInfo['timezone'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">اللغة:</td>
                                    <td>{{ $systemInfo['locale'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">وضع التطوير:</td>
                                    <td>
                                        @if($systemInfo['debug_mode'])
                                            <span class="badge bg-warning">مفعل</span>
                                        @else
                                            <span class="badge bg-success">معطل</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">البيئة:</td>
                                    <td>
                                        <span class="badge bg-{{ $systemInfo['environment'] === 'production' ? 'success' : 'warning' }}">
                                            {{ $systemInfo['environment'] }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Actions -->
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-tools me-2"></i>
                        إجراءات النظام
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('settings.logs') }}" class="btn btn-outline-info">
                            <i class="fas fa-file-alt me-2"></i>
                            عرض سجلات النظام
                        </a>
                        <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                            <i class="fas fa-broom me-2"></i>
                            مسح الذاكرة المؤقتة
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="optimizeApp()">
                            <i class="fas fa-rocket me-2"></i>
                            تحسين التطبيق
                        </button>
                        <a href="{{ route('settings.backup') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>
                            تحميل نسخة احتياطية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 2.5rem;
    margin-left: 15px;
    opacity: 0.8;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    line-height: 1;
}

.stat-label {
    margin: 0;
    opacity: 0.9;
    font-size: 0.9rem;
}
</style>
@endpush

@push('scripts')
<script>
function clearCache() {
    if (confirm('هل أنت متأكد من مسح الذاكرة المؤقتة؟')) {
        fetch('{{ route("settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('تم مسح الذاكرة المؤقتة بنجاح', 'success');
            } else {
                showAlert('حدث خطأ أثناء مسح الذاكرة المؤقتة', 'error');
            }
        })
        .catch(error => {
            showAlert('حدث خطأ أثناء مسح الذاكرة المؤقتة', 'error');
        });
    }
}

function optimizeApp() {
    if (confirm('هل أنت متأكد من تحسين التطبيق؟')) {
        fetch('{{ route("settings.optimize") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('تم تحسين التطبيق بنجاح', 'success');
            } else {
                showAlert('حدث خطأ أثناء تحسين التطبيق', 'error');
            }
        })
        .catch(error => {
            showAlert('حدث خطأ أثناء تحسين التطبيق', 'error');
        });
    }
}

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.fade-in');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>
@endpush

