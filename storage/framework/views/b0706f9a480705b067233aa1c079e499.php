<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم'); ?> - <?php echo e(config('app.name', 'موثوق')); ?></title>

    <!-- Meta Description -->
    <meta name="description" content="منصة موثوق لوساطة الخدمات الحكومية - لوحة تحكم شاملة لإدارة المعاملات الحكومية">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    
    <!-- Additional Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">
    <div id="app">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('dashboard')); ?>">
                    <i class="fas fa-shield-alt me-2"></i>
                    <span>موثوق</span>
                </a>

                <!-- Mobile Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="تبديل التنقل">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Items -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Left Side (appears on right in RTL) -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                                <i class="fas fa-home me-1"></i>
                                الرئيسية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('transactions.index')); ?>">
                                <i class="fas fa-file-alt me-1"></i>
                                المعاملات
                            </a>
                        </li>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('reports.index')); ?>">
                                <i class="fas fa-chart-bar me-1"></i>
                                التقارير
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Right Side (appears on left in RTL) -->
                    <ul class="navbar-nav">
                        <!-- Notifications -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" 
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <?php if(auth()->user()->unread_notifications_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo e(auth()->user()->unread_notifications_count); ?>

                                </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                <li><h6 class="dropdown-header">الإشعارات</h6></li>
                                <?php $__empty_1 = true; $__currentLoopData = auth()->user()->recent_notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <a class="dropdown-item <?php echo e($notification->is_read ? '' : 'fw-bold'); ?>" 
                                       href="<?php echo e($notification->action_url ?: '#'); ?>">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas <?php echo e($notification->icon ?: 'fa-info-circle'); ?> text-<?php echo e($notification->color); ?>"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <div class="fw-semibold"><?php echo e($notification->title); ?></div>
                                                <div class="small text-muted"><?php echo e(Str::limit($notification->message, 50)); ?></div>
                                                <div class="small text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li><span class="dropdown-item text-muted">لا توجد إشعارات جديدة</span></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="<?php echo e(route('notifications.index')); ?>">عرض جميع الإشعارات</a></li>
                            </ul>
                        </li>

                        <!-- User Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="me-2">
                                    <div class="fw-semibold"><?php echo e(auth()->user()->display_name); ?></div>
                                    <div class="small text-light opacity-75"><?php echo e(auth()->user()->user_type_arabic); ?></div>
                                </div>
                                <i class="fas fa-user-circle fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><h6 class="dropdown-header"><?php echo e(auth()->user()->display_name); ?></h6></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>">
                                    <i class="fas fa-user me-2"></i>الملف الشخصي
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('profile.settings')); ?>">
                                    <i class="fas fa-cog me-2"></i>الإعدادات
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid" style="margin-top: 76px;">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" 
                                   href="<?php echo e(route('dashboard')); ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>" 
                                   href="<?php echo e(route('transactions.index')); ?>">
                                    <i class="fas fa-file-alt me-2"></i>
                                    المعاملات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('transactions.create') ? 'active' : ''); ?>" 
                                   href="<?php echo e(route('transactions.create')); ?>">
                                    <i class="fas fa-plus me-2"></i>
                                    معاملة جديدة
                                </a>
                            </li>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" 
                                   href="<?php echo e(route('reports.index')); ?>">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    التقارير
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-users')): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" 
                                   href="<?php echo e(route('users.index')); ?>">
                                    <i class="fas fa-users me-2"></i>
                                    المستخدمون
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings')): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>" 
                                   href="<?php echo e(route('settings.index')); ?>">
                                    <i class="fas fa-cogs me-2"></i>
                                    إعدادات النظام
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>

                <!-- Main Content Area -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <!-- Breadcrumb -->
                    <?php if(isset($breadcrumbs)): ?>
                    <nav aria-label="breadcrumb" class="pt-3">
                        <ol class="breadcrumb">
                            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($loop->last): ?>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($breadcrumb['title']); ?></li>
                                <?php else: ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['title']); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </nav>
                    <?php endif; ?>

                    <!-- Page Header -->
                    <?php if (! empty(trim($__env->yieldContent('page-header')))): ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <?php echo $__env->yieldContent('page-header'); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Flash Messages -->
                    <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                    <?php endif; ?>

                    <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                    <?php endif; ?>

                    <?php if(session('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php echo e(session('info')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Main Content -->
                    <div class="fade-in">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Mark notifications as read when clicked
        document.querySelectorAll('#notificationsDropdown + .dropdown-menu .dropdown-item').forEach(function(item) {
            item.addEventListener('click', function() {
                // Add AJAX call to mark notification as read
                // This would be implemented with the notification system
            });
        });
    </script>
</body>
</html>

<?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\layouts\dashboard-backup.blade.php ENDPATH**/ ?>