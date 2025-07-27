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
    
    <style>
        :root {
            --primary-color: #1e7e34;
            --secondary-color: #28a745;
            --accent-color: #20c997;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
            --text-muted: #6c757d;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background-color: #f5f6fa;
            color: #2c3e50;
        }

        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            border-bottom: 3px solid var(--accent-color);
        }

        .header-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .header-brand:hover {
            color: var(--accent-color) !important;
        }

        .header-brand .brand-icon {
            background: rgba(255,255,255,0.2);
            padding: 8px;
            border-radius: 8px;
            margin-left: 10px;
        }

        .header-nav {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .header-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .header-nav .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: white !important;
        }

        .header-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
        }

        /* User Profile in Header */
        .user-profile {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-left: 12px;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .user-info small {
            opacity: 0.8;
            font-size: 0.75rem;
        }

        /* Sidebar Styles */
        .main-sidebar {
            background: white;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            position: fixed;
            top: var(--header-height);
            right: 0;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            border-left: 1px solid var(--border-color);
            overflow-y: auto;
            z-index: 1020;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .sidebar-menu .menu-header {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .sidebar-menu .menu-item {
            margin: 0.25rem 1rem;
        }

        .sidebar-menu .menu-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--dark-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .sidebar-menu .menu-link:hover {
            background: var(--light-color);
            color: var(--primary-color);
            transform: translateX(-2px);
        }

        .sidebar-menu .menu-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 12px rgba(30, 126, 52, 0.3);
        }

        .sidebar-menu .menu-link.active::before {
            content: '';
            position: absolute;
            right: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 30px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .sidebar-menu .menu-icon {
            width: 20px;
            margin-left: 12px;
            text-align: center;
        }

        .sidebar-menu .menu-badge {
            margin-right: auto;
            background: var(--accent-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin: 0.5rem 0 0 0;
        }

        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }

            .main-sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
                padding: 1rem;
            }

            .page-header {
                padding: 1rem;
            }
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Dropdown Improvements */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--light-color);
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Main Header -->
        <header class="main-header">
            <div class="container-fluid h-100">
                <div class="row h-100 align-items-center">
                    <!-- Brand -->
                    <div class="col-auto">
                        <a href="<?php echo e(route('dashboard')); ?>" class="header-brand">
                            <div class="brand-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span>موثوق</span>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <div class="col">
                        <nav class="header-nav">
                            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                                <i class="fas fa-home me-2"></i>
                                الرئيسية
                            </a>
                            <a href="<?php echo e(route('transactions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>">
                                <i class="fas fa-file-alt me-2"></i>
                                المعاملات
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                            <a href="<?php echo e(route('reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                                <i class="fas fa-chart-bar me-2"></i>
                                التقارير
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>

                    <!-- User Profile & Notifications -->
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <!-- Notifications -->
                            <div class="dropdown me-3">
                                <a href="#" class="nav-link position-relative" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell fa-lg"></i>
                                    <?php if(auth()->user()->unread_notifications_count > 0): ?>
                                    <span class="notification-badge"><?php echo e(auth()->user()->unread_notifications_count); ?></span>
                                    <?php endif; ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span>الإشعارات</span>
                                        <?php if(auth()->user()->unread_notifications_count > 0): ?>
                                        <small class="text-primary"><?php echo e(auth()->user()->unread_notifications_count); ?> جديد</small>
                                        <?php endif; ?>
                                    </div>
                                    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->recent_notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e($notification->action_url ?: '#'); ?>" class="dropdown-item <?php echo e($notification->is_read ? '' : 'fw-bold'); ?>">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas <?php echo e($notification->icon ?: 'fa-info-circle'); ?> text-<?php echo e($notification->color); ?>"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold"><?php echo e($notification->title); ?></div>
                                                <div class="small text-muted"><?php echo e(Str::limit($notification->message, 50)); ?></div>
                                                <div class="small text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="dropdown-item text-muted text-center py-3">
                                        <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                                        لا توجد إشعارات جديدة
                                    </div>
                                    <?php endif; ?>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?php echo e(route('notifications.index')); ?>" class="dropdown-item text-center text-primary">
                                        عرض جميع الإشعارات
                                    </a>
                                </div>
                            </div>

                            <!-- User Profile -->
                            <div class="dropdown">
                                <a href="#" class="user-profile" data-bs-toggle="dropdown">
                                    <div class="user-info text-end">
                                        <h6><?php echo e(auth()->user()->display_name); ?></h6>
                                        <small><?php echo e(auth()->user()->user_type_arabic); ?></small>
                                    </div>
                                    <div class="user-avatar">
                                        <?php echo e(substr(auth()->user()->display_name, 0, 1)); ?>

                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="dropdown-header">
                                        <strong><?php echo e(auth()->user()->display_name); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e(auth()->user()->email); ?></small>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?php echo e(route('profile.show')); ?>" class="dropdown-item">
                                        <i class="fas fa-user me-2"></i>الملف الشخصي
                                    </a>
                                    <a href="<?php echo e(route('profile.settings')); ?>" class="dropdown-item">
                                        <i class="fas fa-cog me-2"></i>الإعدادات
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?php echo e(route('logout')); ?>" class="dropdown-item text-danger"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="main-sidebar">
            <div class="sidebar-menu">
                <div class="menu-header">القائمة الرئيسية</div>
                
                <div class="menu-item">
                    <a href="<?php echo e(route('dashboard')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        <i class="fas fa-tachometer-alt menu-icon"></i>
                        <span>لوحة التحكم</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="<?php echo e(route('transactions.index')); ?>" class="menu-link <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>">
                        <i class="fas fa-file-alt menu-icon"></i>
                        <span>المعاملات</span>
                        <?php if(auth()->user()->pending_transactions_count > 0): ?>
                        <span class="menu-badge"><?php echo e(auth()->user()->pending_transactions_count); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="<?php echo e(route('transactions.create')); ?>" class="menu-link <?php echo e(request()->routeIs('transactions.create') ? 'active' : ''); ?>">
                        <i class="fas fa-plus menu-icon"></i>
                        <span>معاملة جديدة</span>
                    </a>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                <div class="menu-header">التقارير والإحصائيات</div>
                
                <div class="menu-item">
                    <a href="<?php echo e(route('reports.index')); ?>" class="menu-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                        <i class="fas fa-chart-bar menu-icon"></i>
                        <span>التقارير</span>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-users')): ?>
                <div class="menu-header">إدارة النظام</div>
                
                <div class="menu-item">
                    <a href="<?php echo e(route('users.index')); ?>" class="menu-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                        <i class="fas fa-users menu-icon"></i>
                        <span>المستخدمون</span>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings')): ?>
                <div class="menu-item">
                    <a href="<?php echo e(route('settings.index')); ?>" class="menu-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                        <i class="fas fa-cog menu-icon"></i>
                        <span>إعدادات النظام</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <?php if (! empty(trim($__env->yieldContent('page-header')))): ?>
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-start">
                    <?php echo $__env->yieldContent('page-header'); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Flash Messages -->
            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo e(session('warning')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo e(session('info')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Main Content -->
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.main-sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
</body>
</html>

<?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\layouts\dashboard-old.blade.php ENDPATH**/ ?>