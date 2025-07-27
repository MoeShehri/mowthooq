<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم'); ?> - موثوق</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #1e7e34;
            --primary-dark: #155724;
            --primary-light: #28a745;
            --secondary-color: #6c757d;
            --accent-color: #ffc107;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            --border-radius: 0.75rem;
            --border-radius-sm: 0.5rem;
            --border-radius-lg: 1rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            line-height: 1.6;
            color: var(--gray-800);
        }

        /* Modern Header */
        .modern-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }

        .modern-header .navbar-brand {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--white) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modern-header .navbar-brand i {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        .modern-header .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            margin: 0 0.25rem;
        }

        .modern-header .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white) !important;
            transform: translateY(-1px);
        }

        .modern-header .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: var(--white) !important;
        }

        /* User Profile Dropdown */
        .user-profile {
            position: relative;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--accent-color), #e0a800);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark-color);
            cursor: pointer;
            transition: var(--transition);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .user-info {
            margin-right: 1rem;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: var(--white);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .user-role {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            display: inline-block;
        }

        /* Modern Sidebar */
        .modern-sidebar {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            margin: 1.5rem;
            padding: 1.5rem 0;
            height: fit-content;
            position: sticky;
            top: 120px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 0.5rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            margin: 0 1rem;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            transition: var(--transition);
            z-index: -1;
        }

        .sidebar-nav .nav-link:hover::before,
        .sidebar-nav .nav-link.active::before {
            width: 100%;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: var(--white);
            transform: translateX(-5px);
        }

        .sidebar-nav .nav-link i {
            margin-left: 1rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Modern Cards */
        .modern-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            border: none;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .modern-card .card-body {
            padding: 2rem;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stats-card.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
            color: var(--dark-color);
        }

        .stats-card.info {
            background: linear-gradient(135deg, var(--info-color) 0%, #138496 100%);
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%);
        }

        .stats-number {
            font-family: 'Cairo', sans-serif;
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .stats-label {
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.95;
        }

        .stats-icon {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            font-size: 2.5rem;
            opacity: 0.3;
        }

        /* Action Buttons */
        .action-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            color: var(--white);
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition);
        }

        .action-btn:hover::before {
            right: 100%;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, var(--gray-600), var(--gray-700));
        }

        .action-btn.warning {
            background: linear-gradient(135deg, var(--warning-color), #e0a800);
            color: var(--dark-color);
        }

        /* Modern Table */
        .modern-table {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .modern-table .table {
            margin-bottom: 0;
        }

        .modern-table .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            border: none;
            padding: 1.25rem 1rem;
            font-weight: 600;
            text-align: center;
        }

        .modern-table .table tbody td {
            padding: 1rem;
            border-color: var(--gray-200);
            vertical-align: middle;
            text-align: center;
        }

        .modern-table .table tbody tr:hover {
            background: var(--gray-100);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            display: inline-block;
            min-width: 100px;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, var(--warning-color), #e0a800);
            color: var(--dark-color);
        }

        .status-badge.under-review {
            background: linear-gradient(135deg, var(--info-color), #138496);
            color: var(--white);
        }

        .status-badge.completed {
            background: linear-gradient(135deg, var(--success-color), #1e7e34);
            color: var(--white);
        }

        .status-badge.rejected {
            background: linear-gradient(135deg, var(--danger-color), #c82333);
            color: var(--white);
        }

        /* Chart Container */
        .chart-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            position: relative;
        }

        .chart-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--info-color), var(--primary-color));
        }

        /* Notifications */
        .notification-item {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            border-right: 4px solid var(--primary-color);
            transition: var(--transition);
        }

        .notification-item:hover {
            transform: translateX(-5px);
            box-shadow: var(--shadow);
        }

        .notification-item.warning {
            border-right-color: var(--warning-color);
        }

        .notification-item.success {
            border-right-color: var(--success-color);
        }

        .notification-item.danger {
            border-right-color: var(--danger-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quick-action-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .quick-action-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .quick-action-card h5 {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .quick-action-card p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-sidebar {
                margin: 1rem;
                position: static;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            .modern-header .navbar-brand {
                font-size: 1.5rem;
            }
            
            .user-info {
                display: none;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Fade In Animation */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pulse Animation */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(30, 126, 52, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(30, 126, 52, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(30, 126, 52, 0);
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Modern Header -->
    <header class="modern-header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
                    <i class="fas fa-shield-alt"></i>
                    موثوق
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                                <i class="fas fa-home me-2"></i>
                                لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>" href="<?php echo e(route('transactions.index')); ?>">
                                <i class="fas fa-file-alt me-2"></i>
                                المعاملات
                            </a>
                        </li>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-users')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>">
                                <i class="fas fa-users me-2"></i>
                                المستخدمين
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index')); ?>">
                                <i class="fas fa-chart-bar me-2"></i>
                                التقارير
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- User Profile -->
                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <button class="btn btn-link text-white position-relative" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fa-lg"></i>
                                <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo e(auth()->user()->unreadNotifications->count()); ?>

                                </span>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                                <li><h6 class="dropdown-header">الإشعارات</h6></li>
                                <?php $__empty_1 = true; $__currentLoopData = auth()->user()->unreadNotifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="mb-1"><?php echo e($notification->data['message'] ?? 'إشعار جديد'); ?></p>
                                                <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li><span class="dropdown-item-text">لا توجد إشعارات جديدة</span></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">عرض جميع الإشعارات</a></li>
                            </ul>
                        </div>
                        
                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="dropdown">
                                <div class="user-info">
                                    <div class="user-name"><?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?></div>
                                    <div class="user-role"><?php echo e(auth()->user()->getRoleNames()->first() ?? 'مستخدم'); ?></div>
                                </div>
                                <div class="user-avatar">
                                    <?php echo e(substr(auth()->user()->first_name, 0, 1)); ?><?php echo e(substr(auth()->user()->last_name, 0, 1)); ?>

                                </div>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>الإعدادات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2">
                <div class="modern-sidebar">
                    <ul class="sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                                <i class="fas fa-tachometer-alt"></i>
                                لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>" href="<?php echo e(route('transactions.index')); ?>">
                                <i class="fas fa-file-alt"></i>
                                المعاملات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('transactions.create')); ?>">
                                <i class="fas fa-plus"></i>
                                معاملة جديدة
                            </a>
                        </li>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-users')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>">
                                <i class="fas fa-users"></i>
                                المستخدمين
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index')); ?>">
                                <i class="fas fa-chart-bar"></i>
                                التقارير
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-settings')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>" href="<?php echo e(route('settings.index')); ?>">
                                <i class="fas fa-cog"></i>
                                الإعدادات
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-lg-9 col-xl-10">
                <div class="p-4">
                    <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Add loading state to buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>جاري المعالجة...';
                    submitBtn.disabled = true;
                }
            });
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.modern-card, .stats-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views/layouts/dashboard-modern.blade.php ENDPATH**/ ?>