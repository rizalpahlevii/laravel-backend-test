<h1>Framework Laravel V10</h1>

## API CRUD

## System Requirement

- PHP Version 8.1 or Above
- Composer
- Git

## Installation

1. Open the terminal, navigate to your directory (htdocs or public_html).

```bash
git clone https://github.com/rizalpahlevii/laravel-backend-test
cd laravel-backend-test
composer install
cp .env.example .env
```

2. Setting the database configuration, open .env file at project root directory

```
APP_URL=**your_url**
DB_DATABASE=**your_db_name**
DB_USERNAME=**your_db_user**
DB_PASSWORD=**password**
```

3. Clear cache, generate APP KEY, insert dummy data

```bash
php artisan storage:link
php artisan config:cache
php artisan key:generate
php artisan migrate --seed
```

## API Documentation Postman

<a href="Surplus Backend Test.postman_collection.json">Surplus Backend Test.postman_collection.json</a>
