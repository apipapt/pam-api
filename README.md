## Instalasi

- Install [Laravel](https://laravel.com/docs) Menggunakan [Composer](https://getcomposer.org/)
```bash
composer install
```

- Setup Konfigurasi
```bash
cp .env.example .env
```
- Rename Database di .env

- Generate key Konfigurasi
```bash
php artisan key:generate
```

- Install Laravel [Passport](https://laravel.com/docs/9.x/passport#main-content)
```bash
composer require laravel/passport
```

- Migration Table
```bash
php artisan migrate --seed
```

- Miration Token Passport
```bash
php artisan passport:install
```
