# موثوق - Arabic RTL Dashboard Platform

## نظرة عامة | Overview

موثوق هو نظام إدارة معاملات حكومية متطور مصمم خصيصاً للبيئة العربية مع دعم كامل للغة العربية واتجاه النص من اليمين إلى اليسار (RTL). يوفر النظام منصة شاملة لإدارة المعاملات الحكومية والمستخدمين مع واجهة مستخدم احترافية تتماشى مع المعايير السعودية.

Mowthook is an advanced government transaction management system designed specifically for the Arabic environment with full Arabic language support and Right-to-Left (RTL) text direction. The system provides a comprehensive platform for managing government transactions and users with a professional user interface that aligns with Saudi standards.

## المميزات الرئيسية | Key Features

### 🔐 نظام المصادقة وإدارة المستخدمين | Authentication & User Management
- تسجيل دخول آمن مع Laravel Sanctum
- إدارة الأدوار والصلاحيات باستخدام Spatie Laravel Permission
- أربعة أنواع مستخدمين: أفراد، مكاتب هندسية، مطورين عقاريين، مدير
- إدارة شاملة للمستخدمين مع إمكانية التفعيل/الإلغاء

### 📋 إدارة المعاملات | Transaction Management
- إنشاء معاملات جديدة مع رفع المستندات المطلوبة
- تتبع حالة المعاملة في الوقت الفعلي
- موافقة أو رفض المعاملات مع إرسال الأسباب
- نظام مرفقات متقدم لإدارة الملفات

### 🔔 نظام الإشعارات | Notification System
- إشعارات فورية لتغييرات حالة المعاملات
- إشعارات ترحيبية للمستخدمين الجدد
- إشعارات تحديث البيانات
- إمكانية إرسال إشعارات مخصصة من المدير

### 🎨 تصميم احترافي | Professional Design
- واجهة مستخدم عربية كاملة مع دعم RTL
- تصميم متجاوب يعمل على جميع الأجهزة
- ألوان سعودية رسمية (الأخضر الحكومي)
- خطوط عربية احترافية (Tajawal & Cairo)

### 📊 لوحة التحكم والتقارير | Dashboard & Reports
- إحصائيات شاملة للمعاملات والمستخدمين
- رسوم بيانية تفاعلية مع Chart.js
- تصدير التقارير بصيغ مختلفة
- فلترة وبحث متقدم

## المتطلبات التقنية | Technical Requirements

### متطلبات الخادم | Server Requirements
- PHP 8.1 أو أحدث
- MySQL 8.0 أو أحدث
- Composer 2.0+
- Node.js 18.0+
- NPM أو Yarn

### المكتبات المستخدمة | Dependencies
- Laravel 10.x
- Laravel UI (Bootstrap Authentication)
- Spatie Laravel Permission
- Bootstrap 5 RTL
- Chart.js
- Font Awesome
- jQuery

## التثبيت والإعداد | Installation & Setup

### 1. استنساخ المشروع | Clone Repository
```bash
git clone https://github.com/your-repo/mowthook-dashboard.git
cd mowthook-dashboard
```

### 2. تثبيت التبعيات | Install Dependencies
```bash
# تثبيت تبعيات PHP
composer install

# تثبيت تبعيات Node.js
npm install
```

### 3. إعداد البيئة | Environment Setup
```bash
# نسخ ملف البيئة
cp .env.example .env

# توليد مفتاح التطبيق
php artisan key:generate
```

### 4. إعداد قاعدة البيانات | Database Configuration
```bash
# إنشاء قاعدة البيانات
mysql -u root -p -e "CREATE DATABASE mowthook_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# تحديث ملف .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mowthook_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. تشغيل الترحيلات والبذور | Run Migrations & Seeders
```bash
# تشغيل الترحيلات
php artisan migrate

# تشغيل البذور
php artisan db:seed
```

### 6. بناء الأصول | Build Assets
```bash
# للتطوير
npm run dev

# للإنتاج
npm run build
```

### 7. تشغيل الخادم | Start Server
```bash
php artisan serve
```

## بيانات الدخول الافتراضية | Default Login Credentials

### المدير العام | Super Admin
- البريد الإلكتروني: admin@mowthook.sa
- كلمة المرور: admin123

### مستخدم عادي | Regular User
- البريد الإلكتروني: user@mowthook.sa
- كلمة المرور: user123

## هيكل المشروع | Project Structure

```
mowthook-dashboard/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── TransactionController.php
│   │   ├── UserController.php
│   │   ├── ReportsController.php
│   │   └── SettingsController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Transaction.php
│   │   ├── Attachment.php
│   │   └── Notification.php
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── dashboard/
│   │   ├── transactions/
│   │   └── users/
│   ├── sass/
│   └── js/
├── routes/
│   └── web.php
└── ...
```

## الأدوار والصلاحيات | Roles & Permissions

### الأدوار | Roles
1. **مدير عام** (Super Admin) - صلاحيات كاملة
2. **مدير** (Admin) - إدارة المعاملات والمستخدمين
3. **مكتب هندسي** (Engineering Office) - إنشاء ومتابعة المعاملات
4. **فرد** (Individual) - إنشاء ومتابعة المعاملات الشخصية

### الصلاحيات | Permissions
- `view-dashboard` - عرض لوحة التحكم
- `view-transactions` - عرض المعاملات
- `create-transactions` - إنشاء معاملات جديدة
- `edit-transactions` - تعديل المعاملات
- `delete-transactions` - حذف المعاملات
- `manage-transaction-status` - إدارة حالة المعاملات
- `view-users` - عرض المستخدمين
- `create-users` - إنشاء مستخدمين جدد
- `edit-users` - تعديل المستخدمين
- `delete-users` - حذف المستخدمين
- `view-reports` - عرض التقارير
- `export-reports` - تصدير التقارير
- `manage-settings` - إدارة إعدادات النظام
- `send-notifications` - إرسال الإشعارات

## أنواع المعاملات | Transaction Types

1. **رخصة بناء** - Building Permit
2. **رخصة هدم** - Demolition Permit
3. **رخصة تجارية** - Commercial License
4. **رخصة صناعية** - Industrial License
5. **شهادة إتمام بناء** - Building Completion Certificate
6. **رخصة تشغيل** - Operating License
7. **تصريح عمل** - Work Permit
8. **أخرى** - Other

## حالات المعاملات | Transaction Statuses

- **قيد الانتظار** (Pending) - في انتظار المراجعة
- **قيد المراجعة** (Under Review) - تحت المراجعة
- **مطلوب معلومات إضافية** (Additional Info Required) - يحتاج معلومات إضافية
- **مرفوضة** (Rejected) - تم الرفض مع الأسباب
- **موافق عليها** (Approved) - تمت الموافقة
- **مكتملة** (Completed) - اكتملت المعاملة

## الأمان | Security

### حماية البيانات | Data Protection
- تشفير كلمات المرور باستخدام bcrypt
- حماية CSRF لجميع النماذج
- تنظيف وتصفية جميع المدخلات
- رفع آمن للملفات مع فحص الأنواع

### التحكم في الوصول | Access Control
- نظام أدوار وصلاحيات متقدم
- حماية المسارات بالصلاحيات المطلوبة
- جلسات آمنة مع انتهاء صلاحية
- تسجيل العمليات الحساسة

## الاختبار | Testing

### تشغيل الاختبارات | Running Tests
```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبارات محددة
php artisan test --filter=TransactionTest
```

### أنواع الاختبارات | Test Types
- اختبارات الوحدة (Unit Tests)
- اختبارات التكامل (Integration Tests)
- اختبارات المتصفح (Browser Tests)

## النشر | Deployment

### متطلبات الخادم | Server Requirements
- Ubuntu 20.04+ أو CentOS 8+
- Nginx أو Apache
- PHP-FPM
- MySQL أو MariaDB
- SSL Certificate

### خطوات النشر | Deployment Steps
1. رفع الملفات إلى الخادم
2. تثبيت التبعيات
3. إعداد قاعدة البيانات
4. تكوين خادم الويب
5. إعداد SSL
6. تشغيل المهام المجدولة

## الصيانة | Maintenance

### النسخ الاحتياطي | Backup
```bash
# نسخ احتياطي لقاعدة البيانات
mysqldump -u username -p mowthook_db > backup.sql

# نسخ احتياطي للملفات
tar -czf files_backup.tar.gz storage/app/
```

### التحديثات | Updates
```bash
# تحديث التبعيات
composer update
npm update

# تشغيل الترحيلات الجديدة
php artisan migrate

# إعادة بناء الأصول
npm run build
```

## الدعم الفني | Technical Support

### المشاكل الشائعة | Common Issues

#### مشكلة الصلاحيات | Permission Issues
```bash
# إعطاء صلاحيات للمجلدات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### مشكلة الذاكرة | Memory Issues
```bash
# زيادة حد الذاكرة في php.ini
memory_limit = 512M
```

#### مشكلة الرفع | Upload Issues
```bash
# زيادة حد الرفع في php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

## المساهمة | Contributing

### إرشادات المساهمة | Contribution Guidelines
1. Fork المشروع
2. إنشاء فرع جديد للميزة
3. كتابة الاختبارات
4. التأكد من جودة الكود
5. إرسال Pull Request

### معايير الكود | Code Standards
- اتباع PSR-12 لـ PHP
- استخدام ESLint لـ JavaScript
- كتابة تعليقات باللغة العربية والإنجليزية
- اختبار جميع الميزات الجديدة

## الترخيص | License

هذا المشروع مرخص تحت رخصة MIT. راجع ملف LICENSE للمزيد من التفاصيل.

This project is licensed under the MIT License. See the LICENSE file for details.

## الاتصال | Contact

- البريد الإلكتروني: support@mowthook.sa
- الموقع الإلكتروني: https://mowthook.sa
- الدعم الفني: https://support.mowthook.sa

---

**تم التطوير بواسطة فريق موثوق | Developed by Mowthook Team**

© 2025 موثوق - جميع الحقوق محفوظة | All Rights Reserved

