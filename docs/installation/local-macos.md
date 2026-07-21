# Local macOS Setup

Atlas ERP Sprint 0 targets native macOS development with Laravel Herd, PHP 8.4+, Composer, PostgreSQL, Redis, Node.js LTS, and npm.

## Prerequisites

- Laravel Herd with PHP 8.4 or newer
- Composer
- PostgreSQL running locally on `127.0.0.1:5432`
- Redis running locally on `127.0.0.1:6379`
- Node.js LTS and npm

## Project Setup

1. Copy `.env.example` to `.env`.
2. Update database credentials for your local PostgreSQL instance.
3. Run `composer install`.
4. Run `npm install`.
5. Run `php artisan key:generate`.
6. Run `php artisan migrate --seed`.
7. Run `npm run build` for a production-style asset build, or `npm run dev` during development.

## Default Local Database Settings

The shipped `.env.example` is configured for local PostgreSQL development:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=atlas_erp
DB_USERNAME=postgres
DB_PASSWORD=
```

Redis is used for cache, queue, and session infrastructure. If Redis is unavailable, the health page will report that gracefully without exposing secrets.
