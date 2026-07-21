# Tenancy Architecture

Atlas ERP uses a company-based tenancy foundation in Sprint 0. The underlying model is `App\Core\Tenancy\Models\Tenant`, which represents a company record and is surfaced in the administration UI as "Company".

## Current Model

- Each user belongs to one company through `users.tenant_id`.
- Company metadata includes identity, regional defaults, status, and soft-delete support.
- Shared tenant settings are stored in the `settings` table and keyed by `tenant_id`, `group`, and `key`.

## Isolation Approach

- `TenantContext` resolves the authenticated user's company during the request lifecycle.
- The `BelongsToTenant` concern automatically scopes tenant-owned models when a tenant context exists.
- Filament resources for users, companies, and activity logs restrict queries to the current tenant unless the actor is a Super Administrator.
- Administration policies and actions both enforce tenant boundaries so direct or crafted requests cannot bypass UI scoping.

## Sprint 0 Boundaries

- There is no tenant switching yet.
- There is no per-tenant database separation yet.
- The focus is a stable shared-database, row-scoped foundation for later module work.
