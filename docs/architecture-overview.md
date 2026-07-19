# Architecture Overview

Atlas ERP follows a domain-first modular structure.

- `app/Core`: shared platform concerns such as tenancy, settings, notifications, permissions, and dashboard shell.
- `app/CRM`, `app/Operations`, `app/Fleet`, `app/Inventory`, `app/Finance`, `app/Reporting`, `app/Administration`, `app/Shared`: reserved module roots for later sprints.
- Business flow standard: `Controller -> Action -> Service -> Repository -> Model`.
- Multi-tenancy strategy: shared database with tenant-aware scoping and context resolution from authenticated users.
