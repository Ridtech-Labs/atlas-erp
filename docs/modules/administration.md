# Administration Module Foundation

Sprint 0 Administration provides the platform controls needed before business modules are introduced.

## Included in This Sprint

- Company management resource
- User management resource
- Role and permission management resource
- Settings page for general, branding, and notifications preferences
- Business dashboard with workspace greeting, KPI overview, quick actions, upcoming work, and recent activity
- Activity log resource
- Health page with database, cache, queue, storage, and environment checks

## UX Design Language

- Filament remains the visual base, with Atlas ERP layered on top using the existing amber primary brand.
- Navigation is organized for scale: dashboard first, then CRM, Operations, Administration, and Configuration.
- Forms use short helper text, business-oriented labels, and placeholders that explain intent without adding noise.
- Tables favor readable density, consistent empty states, status badges, and search placeholders tied to real workflows.
- Client and job records are presented as workspaces rather than raw CRUD detail screens.

## Dashboard Standards

- The dashboard greets the signed-in user and reinforces the active company workspace.
- KPI cards emphasize business visibility: clients, jobs today, pending approvals, active jobs, overdue jobs, and completed work this month.
- Quick actions focus on the most common operational tasks instead of technical shortcuts.
- Recent activity is concise, tenant-aware, and written as a business timeline.
- Technical health checks live on System Health instead of dominating the landing page.

## Status System

- Client, job, user, and company statuses use consistent wording and badge colors across resources.
- `Active` maps to success, `Inactive` to gray, and `Suspended` or `Cancelled` to danger.
- Workflow states such as `Pending Approval`, `Scheduled`, `In Progress`, and `On Hold` keep their own labels but follow a single badge pattern.
- Priority badges follow the same convention: `Low`, `Normal`, `High`, and `Urgent`.

## Component Conventions

- Empty states should explain what the record type is for and what action the user should take next.
- Workspace sections should lead with the business summary before showing supporting details.
- Bulk destructive actions must require confirmation.
- Read-only system pages should explain why the information matters before listing technical results.

## Future Dashboard Roadmap

- Revenue, receivables, and finance widgets will join the dashboard once the Finance domain is implemented.
- Additional operational widgets can surface fleet readiness, warehouse throughput, and crew allocation once those modules exist.
- Reports remain a future navigation placeholder and should not be exposed until there is real reporting functionality behind them.

## Audit and Notifications

- Administrative changes are written to the activity log with tenant, IP address, and user-agent context where available.
- User creation emits a database notification to company administrators in the same tenant.

## Authentication Expectations

- Login, logout, password reset, and email verification are enabled.
- Inactive and suspended users are blocked from login.
- Users in inactive or suspended companies are blocked from panel access.
- Successful logins record both timestamp and IP address.
