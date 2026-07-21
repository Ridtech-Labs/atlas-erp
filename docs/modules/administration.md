# Administration Module Foundation

Sprint 0 Administration provides the platform controls needed before business modules are introduced.

## Included in This Sprint

- Company management resource
- User management resource
- Role and permission management resource
- Settings page for general, branding, and notifications preferences
- Dashboard shell with current context, active-user metrics, recent activity, and system status
- Activity log resource
- Health page with database, cache, queue, storage, and environment checks

## Audit and Notifications

- Administrative changes are written to the activity log with tenant, IP address, and user-agent context where available.
- User creation emits a database notification to company administrators in the same tenant.

## Authentication Expectations

- Login, logout, password reset, and email verification are enabled.
- Inactive and suspended users are blocked from login.
- Users in inactive or suspended companies are blocked from panel access.
- Successful logins record both timestamp and IP address.
