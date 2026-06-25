# Docker Troubleshooting Guide

This document outlines common Docker issues encountered during the development and deployment of this Laravel application, along with their root causes and solutions.

## 1. Nginx `502 Bad Gateway` (Connection Refused)

**Symptoms:**
- The application throws a `502 Bad Gateway` error in the browser.
- The `hrms-web` (Nginx) logs show: `connect() failed (111: Connection refused) while connecting to upstream... upstream: "fastcgi://172.19.0.x:9000"`
- The `hrms-app` (PHP-FPM) container appears to be running normally (`docker ps`).

**Root Cause:**
Nginx aggressively caches the IP address of its upstream servers (`fastcgi_pass app:9000;`) when it first boots up. If the `app` container crashes or is recreated, Docker assigns it a **new** internal IP address. Nginx, however, will stubbornly keep trying to send traffic to the old, dead IP address, resulting in a connection refusal.

**Solution (Already Implemented):**
To prevent this, Nginx has been configured to use **dynamic DNS resolution**. By using a variable (`$upstream`) combined with Docker's internal DNS resolver (`127.0.0.11`), Nginx is forced to re-evaluate the IP address periodically.

*Reference (`docker/nginx/default.conf`):*
```nginx
    # Use Docker's internal DNS resolver
    resolver 127.0.0.11 valid=10s;

    location ~ \.php$ {
        # Using a variable forces Nginx to dynamically resolve the IP
        set $upstream app:9000;
        fastcgi_pass $upstream;
        ...
    }
```

**Quick Fix if it happens again:**
If the dynamic resolution ever fails, you can manually clear the cache by simply restarting the web container:
```bash
docker-compose restart web
```

---

## 2. Container Boot Crash: Cache Corruption

**Symptoms:**
- The `hrms-app` container exits immediately upon starting or restarting.
- Logs show errors attempting to connect to `127.0.0.1:3306` instead of the `db` host.
- Or, Artisan commands inside the container fail due to incorrect environment variables.

**Root Cause:**
When running Docker locally with volume mounts (`- .:/var/www/html`), Laravel's cache files generated on your host machine (like `bootstrap/cache/config.php`) are mounted directly into the container. Since your host machine uses `127.0.0.1` for the database, the container accidentally loads this cached configuration and tries to connect to itself rather than the `db` container, causing an immediate crash.

**Solution (Already Implemented):**
The `docker/entrypoint.sh` script is explicitly designed to wipe out host-contaminated cache files **before** any Artisan commands are executed. 

*Reference (`docker/entrypoint.sh`):*
```bash
    # Crucial Caching Clear FIRST
    echo "Clearing caches manually to avoid boot crashes..."
    rm -f bootstrap/cache/*.php
```

---

## 3. Migration and Cache Driver Deadlocks

**Symptoms:**
- The container fails to boot during `php artisan cache:clear` or `php artisan route:clear`.
- The error indicates that the `sessions` or `cache` table does not exist.

**Root Cause:**
This project relies entirely on the database for its drivers (`CACHE_STORE=database`, `SESSION_DRIVER=database`). If `artisan cache:clear` is run *before* the database migrations have created the `cache` and `cache_locks` tables, the command will crash the boot sequence.

**Solution (Already Implemented):**
The `docker/entrypoint.sh` script enforces a strict serialization process:
1. Wait for MySQL to be fully ready on port 3306.
2. Manually delete `bootstrap/cache/*.php`.
3. Clear Laravel's configuration (`php artisan config:clear`).
4. **Run Migrations** (`php artisan migrate`).
5. *Only then* clear Laravel caches and routes.
