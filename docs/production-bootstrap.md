# Production Bootstrap

Atlas production initialization must not run `DatabaseSeeder`. That seeder is reserved for local/demo environments and creates demo data.

1. Configure production environment variables, including `APP_ENV=production`, `APP_DEBUG=false`, a real `APP_KEY`, HTTPS `APP_URL`, Redis services, a real mailer, and `MEDIA_DISK=private-s3` or another private disk.
2. Run `php artisan migrate --force`.
3. Run `php artisan atlas:bootstrap-production` interactively. Supply the tenant, operating company, and initial Company Administrator details at the prompt. The password is never accepted as a command-line option.
4. Store the administrator password in the approved secret manager and use Company Administrator user management for subsequent users.
5. Run `php artisan optimize` after deployment and start the queue worker for queued media conversions.

The bootstrap command seeds only canonical roles and permissions. It aborts when the requested tenant slug or administrator email already exists, avoiding accidental duplicate bootstrap records.

## Existing public evidence

New Job Card, Waybill, and VAT receipt uploads use the configured private media disk. Existing deployments can first inspect legacy public evidence with:

```bash
php artisan atlas:migrate-sensitive-media-to-private
```

After storage backup verification, explicitly run the move:

```bash
php artisan atlas:migrate-sensitive-media-to-private --execute
```

The command only targets the three sensitive media collections and is idempotent after a successful move because it selects only rows still recorded on the public disk. Do not run it against QA data without first confirming the listed files and validating backups.
