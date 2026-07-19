# Docker Guide

The repository includes a Docker stack for the Sprint 0 foundation:

- `app`: PHP 8.4 FPM runtime
- `nginx`: web server
- `postgres`: PostgreSQL 17
- `redis`: cache and queue backend
- `queue`: Redis queue worker
- `scheduler`: Laravel scheduler loop
- `mailpit`: local SMTP and inbox

Start the stack with:

```bash
docker compose up -d --build
```
