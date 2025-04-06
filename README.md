# Laravel Project Setup

## Configuration

پس از پیکربندی فایل `.env` دستورات زیر را به ترتیب اجرا کنید:
```bash
composer install
npm install
npm run dev
php artisan key:generate
php artisan serve
php artisan migrate
php artisan db:seed
php artisan optimize
```

اکنون میتوانید آدرس `localhost:8000` را در مرورگر باز کنید.

## Login Credentials

پس از اجرای seeder ها میتوانید با اطلاعات کاربران زیر وارد سامانه شوید:

    Username: admin
    Password: 12345678

    Username: user
    Password: 12345678

## Additional information

عملیات های فیلتر، مرتب سازی و جستجو در سمت کاربر پیاده سازی شده است. *

عملیات اعتبارسنجی و مدیریت خطا در سمت سرور پیاده سازی شده است. *

برای تجربه کاربری بهتر از قابلیت `persisted state` در `Pinia` استفاده شده است ولی لازم به ذکر است که این قابلیت باعص آسیب پذیر شدن پروژه به حملات `XSS` شده است. *
