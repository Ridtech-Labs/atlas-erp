# Local Development Guide

Atlas ERP targets Docker-first development, but local host development is also supported for quick iteration.

- PHP: local machine currently needs a compatible PHP runtime for Composer and Artisan.
- Database: PostgreSQL 17 is the target runtime, while tests default to SQLite in-memory.
- Queue and cache: Redis is the default application configuration.
- Commands:
  - `composer ci`
  - `php artisan migrate --seed`
  - `php artisan test`
  - `npm run dev`
