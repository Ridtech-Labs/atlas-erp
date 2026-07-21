# Authorization Architecture

Atlas ERP Sprint 0 uses `spatie/laravel-permission` for role and permission management.

## Default Roles

- Super Administrator
- Company Administrator
- Operations Manager
- Finance Manager
- Fleet Manager
- Warehouse Manager
- Standard User

## Permission Model

Permissions are seeded idempotently by `RoleAndPermissionSeeder` and cover:

- Dashboard access
- Company administration
- User administration
- Role administration
- Settings access
- Activity log access
- Health page access

## Enforcement Layers

- Policies protect companies, users, roles, activity logs, settings, and health access.
- Administration actions re-check role assignment and tenant access to prevent crafted-request escalation.
- Super Administrator is the only role that can create or delete companies and manage the full role catalogue.
- Company administrators are limited to their own tenant and cannot assign the Super Administrator role.
