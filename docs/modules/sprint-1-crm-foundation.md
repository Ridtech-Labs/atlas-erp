## Sprint 1 CRM and Job Foundation

Sprint 1 introduces the first tenant-aware CRM and job-management domain layer for Atlas ERP.

### Scope

- Clients
- Client contacts
- Client sites
- Jobs

Excluded from Sprint 1:

- Equipment assignments
- Fleet integrations
- Inventory integrations
- Waybills
- Job cards
- Machine rental sheets
- Invoices
- Payments
- Accounting
- Advanced reporting

### Architecture

- `App\CRM\Models\Client` is the root CRM aggregate for customer records.
- `App\CRM\Models\ClientContact` belongs to a client and is tenant-scoped.
- `App\CRM\Models\ClientSite` belongs to a client and is tenant-scoped.
- `App\Operations\Models\Job` belongs to a client and optionally to a client site.
- All Sprint 1 records use integer internal IDs and UUID public identifiers.
- Tenant isolation is enforced with the shared `BelongsToTenant` concern, scoped resource queries, tenant-aware policies, and action-layer validation.

### Relationships

- A tenant has many clients.
- A client has many contacts.
- A client has many sites.
- A client has many jobs.
- A site may have many jobs.

### Numbering approach

- Client codes are unique per tenant.
- Job numbers are unique per tenant.
- Number generation uses the `tenant_sequences` table with row-level locking inside a transaction.
- Atlas ERP does not derive business identifiers with `count() + 1`.

### Site-code rule

- `site_code` is unique per tenant.
- This keeps site identifiers stable even when a client has multiple operational locations.

### Job workflow

Allowed transitions:

- `draft -> pending_approval`
- `pending_approval -> draft`
- `pending_approval -> approved`
- `approved -> scheduled`
- `approved -> cancelled`
- `scheduled -> in_progress`
- `scheduled -> cancelled`
- `in_progress -> on_hold`
- `on_hold -> in_progress`
- `in_progress -> completed`
- `in_progress -> cancelled`

Workflow rules:

- transitions are executed through explicit action classes;
- every transition checks authorization;
- every transition validates the current state;
- transitions run in database transactions;
- approval, completion, and cancellation metadata are written by the transition actions;
- activity events are recorded through the shared administration activity logger.

### Permission matrix

Sprint 1 adds:

- `clients.*`
- `client_contacts.*`
- `client_sites.*`
- `jobs.*`

Current role defaults:

- Super Administrators receive all permissions.
- Company Administrators receive full tenant-bound CRM and job foundation permissions.
- Operations Managers receive tenant-bound read/create/update workflow permissions for operational work.

### Tenant isolation

- Super Administrators may see all tenants where the existing Sprint 0 access architecture allows it.
- Company Administrators remain locked to their own tenant data.
- Cross-tenant client, contact, site, and job associations are rejected in the action layer.
- Filament resource queries are tenant-scoped for non-super-administrators.

### Migration notes

- The operational job aggregate uses the `client_jobs` table, not `jobs`, because Laravel already reserves `jobs` for the queue subsystem.
- Sprint 1 adds forward-only migrations:
  - `tenant_sequences`
  - `clients`
  - `client_contacts`
  - `client_sites`
  - `client_jobs`
