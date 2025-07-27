# دليل النشر - موثوق | Deployment Guide - Mowthook

## نظرة عامة | Overview

هذا الدليل يوضح كيفية نشر منصة موثوق على خادم الإنتاج بطريقة آمنة ومحسنة للأداء. يغطي الدليل جميع الخطوات من إعداد الخادم إلى تكوين النظام للعمل في بيئة الإنتاج.

This guide explains how to deploy the Mowthook platform on a production server in a secure and performance-optimized manner. The guide covers all steps from server setup to system configuration for production environment.

## متطلبات الخادم | Server Requirements

### المتطلبات الأساسية | Basic Requirements
- **نظام التشغيل**: Ubuntu 20.04 LTS أو أحدث
- **المعالج**: 2 CPU cores أو أكثر
- **الذاكرة**: 4GB RAM أو أكثر
- **التخزين**: 50GB SSD أو أكثر
- **الشبكة**: اتصال إنترنت مستقر

### البرامج المطلوبة | Required Software
- PHP 8.1+
- MySQL 8.0+
- Nginx
- Node.js 18+
- Composer
- Git
- Certbot (للـ SSL)

## إعداد الخادم | Server Setup

### 1. تحديث النظام | System Update
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip
```

### 2. تثبيت PHP | PHP Installation
```bash
# إضافة مستودع PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# تثبيت PHP والإضافات المطلوبة
sudo apt install -y php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl \
    php8.1-gd php8.1-mbstring php8.1-zip php8.1-intl php8.1-bcmath \
    php8.1-soap php8.1-redis php8.1-imagick
```

### 3. تثبيت MySQL | MySQL Installation
```bash
# تثبيت MySQL
sudo apt install -y mysql-server

# تأمين MySQL
sudo mysql_secure_installation

# إنشاء قاعدة البيانات والمستخدم
sudo mysql -u root -p
```

```sql
CREATE DATABASE mowthook_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mowthook_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON mowthook_production.* TO 'mowthook_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. تثبيت Nginx | Nginx Installation
```bash
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

### 5. تثبيت Node.js | Node.js Installation
```bash
# تثبيت Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 6. تثبيت Composer | Composer Installation
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

## نشر التطبيق | Application Deployment

### 1. إنشاء مستخدم النشر | Create Deployment User
```bash
# إنشاء مستخدم مخصص للتطبيق
sudo adduser mowthook
sudo usermod -aG www-data mowthook

# التبديل للمستخدم الجديد
sudo su - mowthook
```

### 2. استنساخ المشروع | Clone Project
```bash
# الانتقال لمجلد المنزل
cd /home/mowthook

# استنساخ المشروع
git clone https://github.com/your-repo/mowthook-dashboard.git
cd mowthook-dashboard
```

### 3. تثبيت التبعيات | Install Dependencies
```bash
# تثبيت تبعيات PHP
composer install --optimize-autoloader --no-dev

# تثبيت تبعيات Node.js
npm ci --only=production
```

### 4. إعداد البيئة | Environment Configuration
```bash
# نسخ ملف البيئة
cp .env.example .env

# تحرير ملف البيئة
nano .env
```

```env
APP_NAME="موثوق - Mowthook"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mowthook_production
DB_USERNAME=mowthook_user
DB_PASSWORD=strong_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="موثوق - Mowthook"
```

### 5. توليد مفتاح التطبيق | Generate Application Key
```bash
php artisan key:generate
```

### 6. تشغيل الترحيلات | Run Migrations
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. بناء الأصول | Build Assets
```bash
npm run build
```

### 8. تحسين التطبيق | Optimize Application
```bash
# تحسين التكوين
php artisan config:cache

# تحسين المسارات
php artisan route:cache

# تحسين العروض
php artisan view:cache

# تحسين الأحداث
php artisan event:cache
```

### 9. إعداد الصلاحيات | Set Permissions
```bash
# العودة للمستخدم الجذر
exit

# إعداد الصلاحيات
sudo chown -R mowthook:www-data /home/mowthook/mowthook-dashboard
sudo chmod -R 755 /home/mowthook/mowthook-dashboard
sudo chmod -R 775 /home/mowthook/mowthook-dashboard/storage
sudo chmod -R 775 /home/mowthook/mowthook-dashboard/bootstrap/cache
```

## تكوين Nginx | Nginx Configuration

### 1. إنشاء ملف التكوين | Create Configuration File
```bash
sudo nano /etc/nginx/sites-available/mowthook
```

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /home/mowthook/mowthook-dashboard/public;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Static files caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Security: Hide sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /\.ht {
        deny all;
    }

    # File upload size
    client_max_body_size 10M;

    # Logs
    access_log /var/log/nginx/mowthook_access.log;
    error_log /var/log/nginx/mowthook_error.log;
}
```

### 2. تفعيل الموقع | Enable Site
```bash
# تفعيل الموقع
sudo ln -s /etc/nginx/sites-available/mowthook /etc/nginx/sites-enabled/

# اختبار التكوين
sudo nginx -t

# إعادة تشغيل Nginx
sudo systemctl reload nginx
```

## إعداد SSL | SSL Setup

### 1. تثبيت Certbot | Install Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 2. الحصول على شهادة SSL | Obtain SSL Certificate
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 3. تجديد تلقائي | Auto Renewal
```bash
# إضافة مهمة cron للتجديد التلقائي
sudo crontab -e

# إضافة السطر التالي
0 12 * * * /usr/bin/certbot renew --quiet
```

## تكوين Redis | Redis Configuration

### 1. تثبيت Redis | Install Redis
```bash
sudo apt install -y redis-server
```

### 2. تكوين Redis | Configure Redis
```bash
sudo nano /etc/redis/redis.conf
```

```conf
# تأمين Redis
requirepass your_redis_password

# تحسين الأداء
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### 3. إعادة تشغيل Redis | Restart Redis
```bash
sudo systemctl restart redis-server
sudo systemctl enable redis-server
```

## إعداد المهام المجدولة | Task Scheduling

### 1. إعداد Cron | Setup Cron
```bash
# تحرير crontab للمستخدم
sudo crontab -u mowthook -e

# إضافة مهمة Laravel Scheduler
* * * * * cd /home/mowthook/mowthook-dashboard && php artisan schedule:run >> /dev/null 2>&1
```

### 2. إعداد Queue Worker | Setup Queue Worker
```bash
# إنشاء ملف systemd service
sudo nano /etc/systemd/system/mowthook-worker.service
```

```ini
[Unit]
Description=Mowthook Queue Worker
After=redis.service

[Service]
User=mowthook
Group=www-data
Restart=always
ExecStart=/usr/bin/php /home/mowthook/mowthook-dashboard/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
# تفعيل وتشغيل الخدمة
sudo systemctl enable mowthook-worker
sudo systemctl start mowthook-worker
```

## المراقبة والسجلات | Monitoring & Logging

### 1. إعداد Log Rotation | Setup Log Rotation
```bash
sudo nano /etc/logrotate.d/mowthook
```

```
/home/mowthook/mowthook-dashboard/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 mowthook www-data
    postrotate
        /usr/bin/systemctl reload php8.1-fpm > /dev/null 2>&1 || true
    endscript
}
```

### 2. مراقبة الأداء | Performance Monitoring
```bash
# تثبيت htop لمراقبة النظام
sudo apt install -y htop

# تثبيت iotop لمراقبة القرص
sudo apt install -y iotop

# تثبيت nethogs لمراقبة الشبكة
sudo apt install -y nethogs
```

## النسخ الاحتياطي | Backup

### 1. نسخ احتياطي لقاعدة البيانات | Database Backup
```bash
# إنشاء سكريبت النسخ الاحتياطي
sudo nano /home/mowthook/backup.sh
```

```bash
#!/bin/bash

# متغيرات
DB_NAME="mowthook_production"
DB_USER="mowthook_user"
DB_PASS="strong_password_here"
BACKUP_DIR="/home/mowthook/backups"
DATE=$(date +%Y%m%d_%H%M%S)

# إنشاء مجلد النسخ الاحتياطي
mkdir -p $BACKUP_DIR

# نسخ احتياطي لقاعدة البيانات
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# نسخ احتياطي للملفات
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /home/mowthook/mowthook-dashboard/storage/app/

# حذف النسخ القديمة (أكثر من 30 يوم)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
```

```bash
# إعطاء صلاحية التنفيذ
chmod +x /home/mowthook/backup.sh

# إضافة مهمة cron للنسخ الاحتياطي اليومي
sudo crontab -u mowthook -e

# إضافة السطر التالي
0 2 * * * /home/mowthook/backup.sh >> /home/mowthook/backup.log 2>&1
```

## الأمان | Security

### 1. جدار الحماية | Firewall
```bash
# تفعيل UFW
sudo ufw enable

# السماح بـ SSH
sudo ufw allow ssh

# السماح بـ HTTP و HTTPS
sudo ufw allow 'Nginx Full'

# عرض الحالة
sudo ufw status
```

### 2. تأمين SSH | Secure SSH
```bash
sudo nano /etc/ssh/sshd_config
```

```conf
# تغيير المنفذ الافتراضي
Port 2222

# منع تسجيل الدخول كـ root
PermitRootLogin no

# استخدام المفاتيح فقط
PasswordAuthentication no
PubkeyAuthentication yes

# تحديد المستخدمين المسموح لهم
AllowUsers mowthook
```

```bash
# إعادة تشغيل SSH
sudo systemctl restart ssh

# تحديث جدار الحماية
sudo ufw delete allow ssh
sudo ufw allow 2222
```

### 3. تأمين PHP | Secure PHP
```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

```ini
# إخفاء معلومات PHP
expose_php = Off

# تحديد الوظائف المحظورة
disable_functions = exec,passthru,shell_exec,system,proc_open,popen

# تحديد حجم الرفع
upload_max_filesize = 10M
post_max_size = 10M

# تحديد الذاكرة
memory_limit = 256M

# تحديد وقت التنفيذ
max_execution_time = 60
```

## التحديثات | Updates

### 1. تحديث التطبيق | Application Updates
```bash
# إنشاء سكريبت التحديث
sudo nano /home/mowthook/update.sh
```

```bash
#!/bin/bash

cd /home/mowthook/mowthook-dashboard

# وضع التطبيق في وضع الصيانة
php artisan down

# سحب آخر التحديثات
git pull origin main

# تحديث التبعيات
composer install --optimize-autoloader --no-dev

# تشغيل الترحيلات
php artisan migrate --force

# تحديث الأصول
npm ci --only=production
npm run build

# تحسين التطبيق
php artisan config:cache
php artisan route:cache
php artisan view:cache

# إعادة تشغيل الخدمات
sudo systemctl restart php8.1-fpm
sudo systemctl restart mowthook-worker

# إخراج التطبيق من وضع الصيانة
php artisan up

echo "Update completed successfully"
```

```bash
chmod +x /home/mowthook/update.sh
```

## استكشاف الأخطاء | Troubleshooting

### 1. مشاكل الصلاحيات | Permission Issues
```bash
# إعادة تعيين الصلاحيات
sudo chown -R mowthook:www-data /home/mowthook/mowthook-dashboard
sudo chmod -R 755 /home/mowthook/mowthook-dashboard
sudo chmod -R 775 /home/mowthook/mowthook-dashboard/storage
sudo chmod -R 775 /home/mowthook/mowthook-dashboard/bootstrap/cache
```

### 2. مشاكل قاعدة البيانات | Database Issues
```bash
# فحص اتصال قاعدة البيانات
php artisan tinker
DB::connection()->getPdo();
```

### 3. مشاكل الأداء | Performance Issues
```bash
# فحص استخدام الموارد
htop
iotop
df -h

# فحص سجلات الأخطاء
tail -f /var/log/nginx/mowthook_error.log
tail -f /home/mowthook/mowthook-dashboard/storage/logs/laravel.log
```

### 4. مشاكل SSL | SSL Issues
```bash
# فحص حالة الشهادة
sudo certbot certificates

# تجديد الشهادة يدوياً
sudo certbot renew --dry-run
```

## الخلاصة | Conclusion

بعد اتباع هذا الدليل، ستكون منصة موثوق جاهزة للعمل في بيئة الإنتاج بشكل آمن ومحسن للأداء. تأكد من مراقبة النظام بانتظام وتطبيق التحديثات الأمنية.

After following this guide, the Mowthook platform will be ready to operate in a production environment securely and optimized for performance. Make sure to monitor the system regularly and apply security updates.

## الدعم | Support

للحصول على المساعدة أو الإبلاغ عن المشاكل:
- البريد الإلكتروني: support@mowthook.sa
- الدعم الفني: https://support.mowthook.sa

For assistance or to report issues:
- Email: support@mowthook.sa
- Technical Support: https://support.mowthook.sa

---

**© 2025 موثوق - جميع الحقوق محفوظة | All Rights Reserved**

