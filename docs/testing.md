# Testing

Atlas ERP uses Pest for automated testing.

## Default Strategy

- Feature tests run against SQLite in memory by default.
- Production remains PostgreSQL-oriented.
- CI includes a PostgreSQL compatibility job for migrations and seeders.

## Commands

- `composer test`
- `composer analyse`
- `composer format`
- `composer format:test`
- `composer quality`

## Safety

Testing defaults are isolated from local development databases through `phpunit.xml` and `.env.testing.example`.
