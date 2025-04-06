# Laravel Project Setup

برای راه‌اندازی پروژه،بعد از پیکربندی دیتابیس در فایل .env، مراحل زیر را به ترتیب اجرا کنید:

```bash
composer install
npm install
npm run dev
php artisan serve
php artisan migrate
php artisan test
php artisan db:seed
