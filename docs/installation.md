# Installation Guide

1. Copy `.env.example` to `.env`.
2. Install PHP and Node dependencies with `composer install` and `npm install`, or use Docker.
3. Generate the app key with `php artisan key:generate`.
4. Run `php artisan migrate --seed`.
5. Start the local stack with `php artisan serve` and `npm run dev`, or `docker compose up -d`.

Default seeded admin credentials:

- Email: `admin@atlas-erp.test`
- Password: `password`
