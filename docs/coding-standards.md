# Coding Standards

- Strict types for new foundation classes.
- Business logic stays out of controllers, Blade, and Filament resources.
- Repository and service layers own data access and business rules.
- New business entities should include integer primary keys and public UUIDs.
- Permissions use `resource.action` naming.
- Tests, Pint, and PHPStan are required for merge readiness.
