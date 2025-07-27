<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="منصة موثوق - المنصة الرسمية للمعاملات البلدية الرقمية في المملكة العربية السعودية">
    <meta name="keywords" content="موثوق, معاملات, بلدية, رقمية, المملكة العربية السعودية, خدمات إلكترونية, رؤية 2030">
    
    <title>منصة موثوق - المنصة الرسمية للمعاملات البلدية الرقمية</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #006C35;
            --primary-dark: #00582C;
            --primary-light: #00A651;
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
            color: var(--gray-800);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Government Header */
        .gov-header {
            background-color: var(--primary-color);
            padding: 0.5rem 0;
        }

        .gov-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .gov-header .gov-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--white);
            text-decoration: none;
        }

        .gov-header .gov-logo img {
            height: 40px;
        }

        .gov-header .gov-logo span {
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .gov-header .gov-links {
            display: flex;
            gap: 1.5rem;
        }

        .gov-header .gov-links a {
            color: var(--white);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .gov-header .gov-links a:hover {
            color: var(--accent-color);
        }

        /* Main Navigation */
        .main-nav {
            background-color: var(--white);
            box-shadow: var(--shadow);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .main-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-nav .brand {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .main-nav .brand i {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.5rem;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        .main-nav .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .main-nav .nav-links a {
            color: var(--gray-700);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
        }

        .main-nav .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            right: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: var(--transition);
        }

        .main-nav .nav-links a:hover {
            color: var(--primary-color);
        }

        .main-nav .nav-links a:hover::after {
            width: 100%;
        }

        .main-nav .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .main-nav .auth-buttons .btn-login {
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
        }

        .main-nav .auth-buttons .btn-login:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .main-nav .auth-buttons .btn-register {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
        }

        .main-nav .auth-buttons .btn-register:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .main-nav .auth-buttons .btn-dashboard {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .main-nav .auth-buttons .btn-dashboard:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 108, 53, 0.8), rgba(0, 88, 44, 0.9)), url('/assets/landing/hero-banner.png');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/assets/landing/pattern.png');
            opacity: 0.1;
        }

        .hero-section .container {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-family: 'Cairo', sans-serif;
            font-weight: 900;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 700px;
        }

        .hero-section .hero-buttons {
            display: flex;
            gap: 1rem;
        }

        .hero-section .btn-primary {
            background-color: var(--white);
            color: var(--primary-color);
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 700;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-section .btn-primary:hover {
            background-color: var(--gray-200);
            transform: translateY(-3px);
        }

        .hero-section .btn-secondary {
            background-color: transparent;
            color: var(--white);
            border: 2px solid var(--white);
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 700;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-section .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        /* Features Section */
        .features-section {
            padding: 6rem 0;
            background-color: var(--gray-100);
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 4px;
            background-color: var(--primary-color);
        }

        .section-title p {
            font-size: 1.1rem;
            color: var(--gray-600);
            max-width: 700px;
            margin: 0 auto;
        }

        .feature-card {
            background-color: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow);
            height: 100%;
            transition: var(--transition);
            border-top: 5px solid var(--primary-color);
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .feature-card .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
        }

        .feature-card h3 {
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .feature-card p {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
        }

        /* Services Section */
        .services-section {
            padding: 6rem 0;
            background-color: var(--white);
        }

        .service-card {
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .service-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow);
        }

        .service-card .service-icon {
            background-color: rgba(0, 108, 53, 0.1);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .service-card .service-content {
            flex-grow: 1;
        }

        .service-card h3 {
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .service-card p {
            color: var(--gray-600);
            margin-bottom: 0;
        }

        /* Stats Section */
        .stats-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/assets/landing/pattern.png');
            opacity: 0.1;
        }

        .stats-section .container {
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-family: 'Cairo', sans-serif;
            font-weight: 900;
            font-size: 3rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .stat-number i {
            font-size: 2rem;
        }

        .stat-label {
            font-size: 1.2rem;
            font-weight: 600;
        }

        /* Steps Section */
        .steps-section {
            padding: 6rem 0;
            background-color: var(--gray-100);
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .step-number {
            background-color: var(--primary-color);
            color: var(--white);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            flex-shrink: 0;
            position: relative;
        }

        .step-number::after {
            content: '';
            position: absolute;
            top: 60px;
            right: 50%;
            transform: translateX(50%);
            width: 2px;
            height: calc(100% + 1rem);
            background-color: var(--primary-color);
        }

        .step-item:last-child .step-number::after {
            display: none;
        }

        .step-content {
            flex-grow: 1;
        }

        .step-content h3 {
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .step-content p {
            color: var(--gray-600);
            margin-bottom: 0;
        }

        /* Partners Section */
        .partners-section {
            padding: 5rem 0;
            background-color: var(--white);
        }

        .partners-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 3rem;
            margin-top: 3rem;
        }

        .partner-logo {
            max-width: 150px;
            height: auto;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: var(--transition);
        }

        .partner-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
        }

        /* CTA Section */
        .cta-section {
            padding: 5rem 0;
            background: linear-gradient(rgba(0, 108, 53, 0.9), rgba(0, 88, 44, 0.95)), url('/assets/landing/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            color: var(--white);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/assets/landing/pattern.png');
            opacity: 0.1;
        }

        .cta-section .container {
            position: relative;
            z-index: 1;
        }

        .cta-section h2 {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-section .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .cta-section .btn-primary {
            background-color: var(--white);
            color: var(--primary-color);
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 700;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cta-section .btn-primary:hover {
            background-color: var(--gray-200);
            transform: translateY(-3px);
        }

        .cta-section .btn-secondary {
            background-color: transparent;
            color: var(--white);
            border: 2px solid var(--white);
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 700;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cta-section .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        /* Footer */
        .footer {
            background-color: var(--gray-900);
            color: var(--gray-400);
            padding: 5rem 0 0;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--white);
            text-decoration: none;
            margin-bottom: 1.5rem;
        }

        .footer-logo i {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.5rem;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        .footer-logo span {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
        }

        .footer-about {
            margin-bottom: 2rem;
        }

        .footer h4 {
            color: var(--white);
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--white);
            transform: translateX(-5px);
        }

        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-contact li {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .footer-contact li i {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-top: 0.2rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .footer-social a {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .footer-social a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 1.5rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .footer-bottom p {
            margin-bottom: 0;
        }

        .footer-bottom a {
            color: var(--primary-light);
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section p {
                font-size: 1.1rem;
            }
            
            .main-nav .nav-links {
                display: none;
            }
            
            .step-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .step-number::after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .gov-header .gov-links {
                display: none;
            }
            
            .hero-section {
                padding: 4rem 0;
            }
            
            .hero-section .hero-buttons {
                flex-direction: column;
            }
            
            .cta-section .cta-buttons {
                flex-direction: column;
            }
            
            .stat-item {
                margin-bottom: 2rem;
            }
            
            .service-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }

        /* Animation Classes */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-up.active {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in {
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .fade-in.active {
            opacity: 1;
        }

        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            height: 100vh;
            background-color: var(--white);
            z-index: 1001;
            padding: 2rem;
            transition: right 0.3s ease;
            box-shadow: var(--shadow-lg);
            overflow-y: auto;
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-menu-close {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: none;
            border: none;
            color: var(--gray-700);
            font-size: 1.5rem;
            cursor: pointer;
        }

        .mobile-menu-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .mobile-menu-logo {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .mobile-menu-logo i {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.5rem;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        .mobile-menu-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-menu-links li {
            margin-bottom: 1rem;
        }

        .mobile-menu-links a {
            color: var(--gray-700);
            text-decoration: none;
            font-weight: 600;
            display: block;
            padding: 0.75rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .mobile-menu-links a:hover {
            background-color: rgba(0, 108, 53, 0.1);
            color: var(--primary-color);
        }

        .mobile-menu-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Scroll to Top Button */
        .scroll-top {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            background-color: var(--primary-color);
            color: var(--white);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--shadow);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
        }

        .scroll-top.active {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- Government Header -->
    <div class="gov-header">
        <div class="container">
            <a href="#" class="gov-logo">
                <img src="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.11.0/flags/4x3/sa.svg" alt="Saudi Arabia">
                <span>بوابة الخدمات الإلكترونية الحكومية</span>
            </a>
            <div class="gov-links">
                <a href="#">الخدمات الإلكترونية</a>
                <a href="#">رؤية 2030</a>
                <a href="#">اتصل بنا</a>
                <a href="#">English</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <a href="#" class="brand">
                <i class="fas fa-shield-alt"></i>
                موثوق
            </a>
            
            <div class="nav-links">
                <a href="#features">المميزات</a>
                <a href="#services">الخدمات</a>
                <a href="#steps">كيفية الاستخدام</a>
                <a href="#stats">إحصائيات</a>
                <a href="#contact">اتصل بنا</a>
            </div>
            
            <div class="auth-buttons">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        لوحة التحكم
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn-login">تسجيل الدخول</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-register">إنشاء حساب</a>
                <?php endif; ?>
            </div>
            
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <button class="mobile-menu-close">
            <i class="fas fa-times"></i>
        </button>
        <div class="mobile-menu-header">
            <a href="#" class="mobile-menu-logo">
                <i class="fas fa-shield-alt"></i>
                <span>موثوق</span>
            </a>
        </div>
        <ul class="mobile-menu-links">
            <li><a href="#features">المميزات</a></li>
            <li><a href="#services">الخدمات</a></li>
            <li><a href="#steps">كيفية الاستخدام</a></li>
            <li><a href="#stats">إحصائيات</a></li>
            <li><a href="#contact">اتصل بنا</a></li>
        </ul>
        <div class="mobile-menu-buttons">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    لوحة التحكم
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-login">تسجيل الدخول</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-register">إنشاء حساب</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="overlay"></div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-left">
                    <h1>منصة موثوق للمعاملات البلدية الرقمية</h1>
                    <p>المنصة الرسمية للمعاملات البلدية الرقمية في المملكة العربية السعودية. تقدم خدمات إلكترونية متكاملة للأفراد والمكاتب الهندسية والمطورين العقاريين بكفاءة وسرعة عالية.</p>
                    <div class="hero-buttons">
                        <a href="<?php echo e(route('register')); ?>" class="btn-primary">
                            <i class="fas fa-user-plus"></i>
                            إنشاء حساب جديد
                        </a>
                        <a href="#features" class="btn-secondary">
                            <i class="fas fa-info-circle"></i>
                            تعرف على المميزات
                        </a>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-right">
                    <img src="/assets/landing/hero-illustration.png" alt="موثوق" class="img-fluid d-none d-lg-block">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>مميزات منصة موثوق</h2>
                <p>تقدم منصة موثوق مجموعة من المميزات المتكاملة لتسهيل وتسريع المعاملات البلدية الرقمية</p>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <img src="/assets/landing/icon-digital.png" alt="التحول الرقمي" class="feature-icon">
                        <h3>التحول الرقمي</h3>
                        <p>تحويل كافة المعاملات البلدية إلى معاملات رقمية بالكامل مما يوفر الوقت والجهد ويسرع إنجاز المعاملات.</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <img src="/assets/landing/icon-security.png" alt="الأمان والموثوقية" class="feature-icon">
                        <h3>الأمان والموثوقية</h3>
                        <p>أعلى معايير الأمان لحماية البيانات والمعلومات الشخصية والمستندات مع توثيق كامل للمعاملات.</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <img src="/assets/landing/icon-municipality.png" alt="خدمات البلدية" class="feature-icon">
                        <h3>خدمات البلدية</h3>
                        <p>تكامل مع كافة خدمات البلدية الإلكترونية مما يتيح إنجاز المعاملات بسلاسة وسرعة عالية.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>خدماتنا</h2>
                <p>نقدم مجموعة متكاملة من الخدمات الإلكترونية لتلبية احتياجات كافة المستفيدين</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="service-content">
                            <h3>إدارة المعاملات</h3>
                            <p>إنشاء ومتابعة وإدارة كافة المعاملات البلدية بشكل إلكتروني كامل مع إمكانية تتبع حالة المعاملة في كل مرحلة.</p>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="service-content">
                            <h3>خدمات المكاتب الهندسية</h3>
                            <p>خدمات متخصصة للمكاتب الهندسية تشمل تقديم المخططات والتصاميم ومتابعة الموافقات والتراخيص.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-city"></i>
                        </div>
                        <div class="service-content">
                            <h3>خدمات المطورين العقاريين</h3>
                            <p>خدمات شاملة للمطورين العقاريين تتضمن إدارة المشاريع والحصول على التراخيص اللازمة ومتابعة الموافقات.</p>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="service-content">
                            <h3>خدمات الأفراد</h3>
                            <p>خدمات متنوعة للأفراد تشمل تقديم طلبات رخص البناء والترميم والهدم ومتابعة حالة الطلبات.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-users"></i>
                            <span class="counter">50</span>+
                        </div>
                        <div class="stat-label">ألف مستخدم</div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-file-alt"></i>
                            <span class="counter">100</span>+
                        </div>
                        <div class="stat-label">ألف معاملة</div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-building"></i>
                            <span class="counter">500</span>+
                        </div>
                        <div class="stat-label">مكتب هندسي</div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-city"></i>
                            <span class="counter">200</span>+
                        </div>
                        <div class="stat-label">مطور عقاري</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="steps-section" id="steps">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>كيفية استخدام المنصة</h2>
                <p>خطوات بسيطة للبدء في استخدام منصة موثوق وإنجاز معاملاتك بكل سهولة</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="step-item" data-aos="fade-left">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>إنشاء حساب</h3>
                            <p>قم بإنشاء حساب جديد على المنصة واختر نوع الحساب المناسب (فرد، مكتب هندسي، مطور عقاري).</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-aos="fade-right">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>إكمال الملف الشخصي</h3>
                            <p>قم بإكمال بيانات الملف الشخصي وإضافة المعلومات المطلوبة حسب نوع الحساب.</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-aos="fade-left">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>إنشاء معاملة جديدة</h3>
                            <p>قم بإنشاء معاملة جديدة واختر نوع المعاملة المطلوبة وأدخل البيانات اللازمة.</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-aos="fade-right">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3>متابعة حالة المعاملة</h3>
                            <p>تابع حالة المعاملة من خلال لوحة التحكم واستلم الإشعارات بأي تحديثات على المعاملة.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="partners-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>شركاؤنا</h2>
                <p>نفتخر بشراكتنا مع العديد من الجهات الحكومية والخاصة لتقديم أفضل الخدمات</p>
            </div>
            
            <div class="partners-logos" data-aos="fade-up">
                <img src="https://www.my.gov.sa/wps/wcm/connect/1815c8004d2f0a709f5adf7d2b61c077/logo.png?MOD=AJPERES" alt="وزارة الشؤون البلدية والقروية والإسكان" class="partner-logo">
                <img src="https://www.my.gov.sa/wps/wcm/connect/621a40004d2f0a709f5cdf7d2b61c077/logo.png?MOD=AJPERES" alt="وزارة الداخلية" class="partner-logo">
                <img src="https://www.my.gov.sa/wps/wcm/connect/621a40004d2f0a709f5cdf7d2b61c077/logo.png?MOD=AJPERES" alt="الهيئة السعودية للمهندسين" class="partner-logo">
                <img src="https://www.my.gov.sa/wps/wcm/connect/621a40004d2f0a709f5cdf7d2b61c077/logo.png?MOD=AJPERES" alt="الهيئة العامة للعقار" class="partner-logo">
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 data-aos="fade-up">ابدأ الآن في استخدام منصة موثوق</h2>
            <p data-aos="fade-up" data-aos-delay="100">انضم إلى آلاف المستخدمين الذين يستفيدون من خدمات منصة موثوق لإنجاز معاملاتهم البلدية بكل سهولة وسرعة.</p>
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                <a href="<?php echo e(route('register')); ?>" class="btn-primary">
                    <i class="fas fa-user-plus"></i>
                    إنشاء حساب جديد
                </a>
                <a href="<?php echo e(route('login')); ?>" class="btn-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    تسجيل الدخول
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <a href="#" class="footer-logo">
                        <i class="fas fa-shield-alt"></i>
                        <span>موثوق</span>
                    </a>
                    <div class="footer-about">
                        <p>منصة موثوق هي المنصة الرسمية للمعاملات البلدية الرقمية في المملكة العربية السعودية، تهدف إلى تسهيل وتسريع إنجاز المعاملات البلدية بكفاءة عالية.</p>
                    </div>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4">
                    <h4>روابط سريعة</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-angle-left"></i> الرئيسية</a></li>
                        <li><a href="#features"><i class="fas fa-angle-left"></i> المميزات</a></li>
                        <li><a href="#services"><i class="fas fa-angle-left"></i> الخدمات</a></li>
                        <li><a href="#steps"><i class="fas fa-angle-left"></i> كيفية الاستخدام</a></li>
                        <li><a href="#stats"><i class="fas fa-angle-left"></i> إحصائيات</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4">
                    <h4>خدماتنا</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-angle-left"></i> إدارة المعاملات</a></li>
                        <li><a href="#"><i class="fas fa-angle-left"></i> خدمات المكاتب الهندسية</a></li>
                        <li><a href="#"><i class="fas fa-angle-left"></i> خدمات المطورين العقاريين</a></li>
                        <li><a href="#"><i class="fas fa-angle-left"></i> خدمات الأفراد</a></li>
                        <li><a href="#"><i class="fas fa-angle-left"></i> الدعم الفني</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4 mb-4">
                    <h4>اتصل بنا</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>المملكة العربية السعودية، الرياض، حي العليا، شارع العروبة</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>920000000</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@mowthook.sa</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>ساعات العمل: الأحد - الخميس، 8:00 ص - 4:00 م</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>© 2025 منصة موثوق. جميع الحقوق محفوظة. | تطوير بواسطة <a href="#">وزارة الشؤون البلدية والقروية والإسكان</a></p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Counter Up
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
        
        // Mobile Menu Toggle
        $('.mobile-menu-toggle').on('click', function() {
            $('.mobile-menu').addClass('active');
            $('.overlay').addClass('active');
            $('body').css('overflow', 'hidden');
        });
        
        $('.mobile-menu-close, .overlay').on('click', function() {
            $('.mobile-menu').removeClass('active');
            $('.overlay').removeClass('active');
            $('body').css('overflow', 'auto');
        });
        
        // Smooth Scrolling
        $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && 
                location.hostname == this.hostname
            ) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                    
                    // Close mobile menu if open
                    $('.mobile-menu').removeClass('active');
                    $('.overlay').removeClass('active');
                    $('body').css('overflow', 'auto');
                }
            }
        });
        
        // Scroll to Top Button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.scroll-top').addClass('active');
            } else {
                $('.scroll-top').removeClass('active');
            }
        });
        
        $('.scroll-top').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    </script>
</body>
</html>

<?php /**PATH C:\laragon\www\mowthook-dashboard\resources\views\welcome.blade.php ENDPATH**/ ?>