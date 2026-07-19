# Testing Notes

Sprint 0 uses Pest with Laravel's `RefreshDatabase` trait for feature coverage.

## Current Focus Areas

- Authentication and session flows
- Tenant isolation for tenant-scoped data
- Permission enforcement
- Administration foundation behavior such as login restrictions, tenant-boundary checks, and tenant-scoped activity visibility

## Recommended Commands

```bash
php artisan test
composer analyse
composer pint
```

Run the full suite after schema, authorization, or Filament administration changes so tenancy and permission regressions are caught early.
