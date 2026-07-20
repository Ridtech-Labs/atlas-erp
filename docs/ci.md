# Continuous Integration

The primary workflow lives at `.github/workflows/ci.yml`.

## Quality Job

The main quality job runs on SQLite and performs:

- Composer install
- Node install
- Frontend build
- Migrations
- Pint in test mode
- PHPStan
- Pest

## PostgreSQL Compatibility Job

A separate job validates:

- PostgreSQL connectivity
- Migrations
- Seeders

This keeps the general feedback loop fast while still checking PostgreSQL compatibility in CI.
