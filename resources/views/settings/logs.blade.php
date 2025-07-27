@extends('layouts.dashboard')

@section('title', 'سجلات النظام')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">سجلات النظام</h2>
                    <p class="text-muted mb-0">عرض سجلات النظام والأخطاء</p>
                </div>
                <div>
                    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للإعدادات
                    </a>
                    <button class="btn btn-primary" onclick="refreshLogs()">
                        <i class="fas fa-sync-alt me-2"></i>
                        تحديث
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Display -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            سجلات Laravel (آخر 100 سطر)
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="filterLogs('all')">الكل</button>
                            <button class="btn btn-outline-danger" onclick="filterLogs('error')">أخطاء</button>
                            <button class="btn btn-outline-warning" onclick="filterLogs('warning')">تحذيرات</button>
                            <button class="btn btn-outline-info" onclick="filterLogs('info')">معلومات</button>
                        </div>
                    </div>

                    @if(empty($logs))
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد سجلات متاحة</h5>
                            <p class="text-muted">لم يتم العثور على ملف السجلات أو أنه فارغ</p>
                        </div>
                    @else
                        <div class="logs-container" style="max-height: 600px; overflow-y: auto;">
                            <pre class="logs-content bg-dark text-light p-3 rounded" style="font-size: 12px; line-height: 1.4;">@foreach($logs as $log){{ $log }}
@endforeach</pre>
                        </div>

                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        عدد الأسطر: {{ count($logs) }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        آخر تحديث: {{ now()->format('Y-m-d H:i:s') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Log Statistics -->
    @if(!empty($logs))
    <div class="row mt-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-chart-pie me-2"></i>
                        إحصائيات السجلات
                    </h5>
                    <div class="row" id="logStats">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-danger text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number" id="errorCount">0</h3>
                                    <p class="stat-label">أخطاء</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-warning text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number" id="warningCount">0</h3>
                                    <p class="stat-label">تحذيرات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-info text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number" id="infoCount">0</h3>
                                    <p class="stat-label">معلومات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="stat-card bg-secondary text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ count($logs) }}</h3>
                                    <p class="stat-label">إجمالي الأسطر</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.logs-container {
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.logs-content {
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
}

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

.log-line {
    margin: 0;
    padding: 2px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.log-error {
    color: #ff6b6b !important;
}

.log-warning {
    color: #feca57 !important;
}

.log-info {
    color: #48cae4 !important;
}

.log-debug {
    color: #a8e6cf !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    analyzeLogs();
});

function refreshLogs() {
    window.location.reload();
}

function filterLogs(type) {
    const logsContent = document.querySelector('.logs-content');
    const lines = logsContent.textContent.split('\n');
    
    let filteredLines = lines;
    
    if (type !== 'all') {
        filteredLines = lines.filter(line => {
            const lowerLine = line.toLowerCase();
            switch(type) {
                case 'error':
                    return lowerLine.includes('error') || lowerLine.includes('exception');
                case 'warning':
                    return lowerLine.includes('warning');
                case 'info':
                    return lowerLine.includes('info');
                default:
                    return true;
            }
        });
    }
    
    logsContent.textContent = filteredLines.join('\n');
    
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

function analyzeLogs() {
    const logsContent = document.querySelector('.logs-content');
    if (!logsContent) return;
    
    const lines = logsContent.textContent.split('\n');
    let errorCount = 0;
    let warningCount = 0;
    let infoCount = 0;
    
    lines.forEach(line => {
        const lowerLine = line.toLowerCase();
        if (lowerLine.includes('error') || lowerLine.includes('exception')) {
            errorCount++;
        } else if (lowerLine.includes('warning')) {
            warningCount++;
        } else if (lowerLine.includes('info')) {
            infoCount++;
        }
    });
    
    // Update counters
    const errorCountEl = document.getElementById('errorCount');
    const warningCountEl = document.getElementById('warningCount');
    const infoCountEl = document.getElementById('infoCount');
    
    if (errorCountEl) errorCountEl.textContent = errorCount;
    if (warningCountEl) warningCountEl.textContent = warningCount;
    if (infoCountEl) infoCountEl.textContent = infoCount;
    
    // Highlight log lines
    highlightLogLines();
}

function highlightLogLines() {
    const logsContent = document.querySelector('.logs-content');
    if (!logsContent) return;
    
    let content = logsContent.innerHTML;
    
    // Highlight different log levels
    content = content.replace(/^.*ERROR.*$/gm, '<span class="log-error">$&</span>');
    content = content.replace(/^.*WARNING.*$/gm, '<span class="log-warning">$&</span>');
    content = content.replace(/^.*INFO.*$/gm, '<span class="log-info">$&</span>');
    content = content.replace(/^.*DEBUG.*$/gm, '<span class="log-debug">$&</span>');
    
    logsContent.innerHTML = content;
}
</script>
@endpush

