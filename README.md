# Atlas ERP

Atlas ERP is a pre-release, multi-tenant enterprise resource planning platform built on Laravel 12 and Filament 4.

## Status

This repository is currently in Sprint 0 foundation work. It is not production-ready and should be treated as an internal pre-release codebase.

## Stack

- PHP 8.4+
- Laravel 12
- Filament 4
- Livewire and Volt
- PostgreSQL
- Redis
- Vite and Tailwind CSS
- Pest
- PHPStan with Larastan
- Laravel Pint

## Requirements

- Laravel Herd or another local PHP runtime
- PHP 8.4 or newer
- Composer
- PostgreSQL
- Redis optional for local work, supported for production
- Node.js LTS and npm

## Quick Start

For native macOS development with Laravel Herd:

1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. Update PostgreSQL credentials in `.env`
5. `php artisan migrate --seed`
6. `npm install`
7. `npm run build`

## PostgreSQL Setup

Default local placeholders in `.env.example` are:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=atlas_erp
DB_USERNAME=postgres
DB_PASSWORD=
```

## Environment Notes

If Redis is unavailable in local development, these safe fallbacks are supported:

```dotenv
QUEUE_CONNECTION=sync
CACHE_STORE=file
SESSION_DRIVER=file
```

Production deployments can still use Redis-backed cache, queue, and session infrastructure.

## Common Commands

- Initial setup: `composer setup`
- Reset database: `composer db:reset`
- Run tests: `composer test`
- Run static analysis: `composer analyse`
- Run formatter: `composer format`
- Check formatter only: `composer format:test`
- Run all quality checks: `composer quality`
- Start full local development loop: `composer dev`
- Start Vite only: `npm run dev`

## Branching

- `main` is the stable branch
- `develop` is the active integration branch
- feature work should use branches such as `feature/s0-quality-ci`

## Documentation

Project documentation lives in [`docs/`](/Users/ridwankadri/Desktop/code/atlas-erp/docs).

## Security

Do not report security issues through public issues or public pull requests. Coordinate privately with the repository maintainers.

## License

License status is currently under review. Do not assume the project is open for unrestricted redistribution.
