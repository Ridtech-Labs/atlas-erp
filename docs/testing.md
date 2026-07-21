# Testing

Atlas ERP uses Pest for automated testing during Sprint 0.

## Default Strategy

- Feature tests use Laravel's `RefreshDatabase` trait.
- Tests default to isolated local test configuration so development databases are not reused accidentally.
- Production remains PostgreSQL-oriented, while CI can still exercise compatibility paths separately.

## Current Focus Areas

- Authentication and session flows
- Tenant isolation for tenant-scoped data
- Permission enforcement
- Administration foundation behavior such as login restrictions, tenant-boundary checks, and tenant-scoped activity visibility

## Commands

- `composer test`
- `php artisan test`
- `composer analyse`
- `./vendor/bin/phpstan analyse --memory-limit=1G`
- `composer format`
- `composer format:test`
- `composer quality`

## Safety

Testing defaults are isolated from local development databases through the test environment configuration. Run the full suite after schema, authorization, Filament administration, or tenancy changes so regressions are caught early.
