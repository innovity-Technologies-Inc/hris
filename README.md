# HRMS - Human Resource Management System

A robust Human Resource Management System (HRMS) built with Laravel 12, following Service-Oriented Architecture (SOA).

## Installation Guide

### 1. Prerequisites

Ensure you have the following installed:

- PHP 8.3 or higher
- **Required PHP Extensions:** `intl`, `bcmath`, `curl`, `mbstring`, `openssl`, `xml`, `zip`
- Composer
- MySQL or PostgreSQL
- Node.js & NPM (for PDF generation via Browsershot)

> **Note on `php-intl`:** This extension is required for high-fidelity "Number to Words" conversion (used in Payslips/Certificates). While the project includes a pure PHP fallback, it is highly recommended to enable `intl` for optimal performance.

#### How to install `php-intl`:
- **Ubuntu/Debian:**
  ```bash
  sudo apt-get install php8.3-intl
  sudo service apache2 restart # or sudo service php8.3-fpm restart
  ```
- **CentOS/RHEL:**
  ```bash
  sudo yum install php-intl
  ```
- **Windows (XAMPP/Laragon/Herd):**
  1. Open your `php.ini` file.
  2. Find `;extension=intl` and remove the semicolon (`;`) to uncomment it.
  3. Restart your server.

### 2. Database Setup

Create a new database for the project (e.g., `hrms_db`).

### 3. Environment Configuration

Copy the `.env.example` file to create your `.env` file:

```bash
cp .env.example .env
```

Open the `.env` file and update the following database connection details with your own username and password:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Install Dependencies

Run the following commands to install the project's dependencies:

```bash
composer install
npm install
```

### 5. PDF Generation Setup (Spatie Browsershot)

This project uses **Spatie Browsershot** for generating Payslips and ID Cards. This requires **Node.js**, **Puppeteer**, and a headless **Chrome/Chromium** browser.

#### A. Install Puppeteer
If you haven't run `npm install` yet, do so now. It will automatically install Puppeteer as defined in `package.json`.
```bash
npm install puppeteer
```

#### B. Linux/Server Specifics
If you are deploying on a Linux server (Ubuntu/Debian), you must install the necessary Chromium dependencies:
```bash
sudo apt-get update
sudo apt-get install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libnss3 lsb-release xdg-utils wget
```

#### C. Troubleshooting (Windows)
If you encounter "Node not found" or "Chrome not found" errors on Windows, update your `.env` file with the absolute paths:
```env
BROWSERSHOT_NODE_BINARY="C:\Program Files\nodejs\node.exe"
BROWSERSHOT_NPM_BINARY="C:\Program Files\nodejs\npm.cmd"
# Chrome is usually auto-detected, but can be specified:
# BROWSERSHOT_CHROME_PATH="C:\Program Files\Google\Chrome\Application\chrome.exe"
```

#### D. Docker & Kubernetes (Production / DevOps Setup)
Spatie Browsershot runs a headless Chromium browser instance under Node.js. For containerized environments:

1. **System Dependencies (Dockerfile)**:
   The base Docker image must install Node.js, NPM, and Chromium. This has been pre-configured in our [Dockerfile](file:///P:/Project/Web/hrms/Dockerfile) via the `apt-get` utility:
   ```dockerfile
   RUN apt-get update && apt-get install -y \
       nodejs \
       npm \
       chromium \
       && rm -rf /var/lib/apt/lists/*
   ```

2. **Chrome Path Config (Environment Variable)**:
   Specify the path to the container's Chromium binary. The application is configured to read the path from the `BROWSERSHOT_CHROME_PATH` environment variable.
   - **Local Docker Compose**: Already pre-configured under `environment` in `docker-compose.yml` for `app`, `queue-worker`, and `scheduler` services:
     ```yaml
     environment:
       - BROWSERSHOT_CHROME_PATH=/usr/bin/chromium
     ```
   - **Kubernetes Deployments**: Inject the environment variable directly into the pods in your `deployment.yaml` manifest:
     ```yaml
     containers:
       - name: app
         image: hrms-app:latest
         env:
           - name: BROWSERSHOT_CHROME_PATH
             value: "/usr/bin/chromium"
     ```

3. **Kubernetes Health Probes**:
   If utilizing socket/exec healthchecks in Kubernetes to test if the PHP container is ready (e.g. running TCP connection checks on port 9000), make sure to configure `initialDelaySeconds: 120` to allow the container's startup migrations, cache clearing, and volume permission checks to finish before the probes begin.

### 6. Application Optimization

Clear the configuration cache and optimize the application:

```bash
php artisan config:clear
php artisan optimize
```

### 6. Database Migration
Run the database migrations to create the necessary tables:
```bash
php artisan migrate
```

### 7. Automated Tasks & Alerts (Required)

This project includes an automated alert system for birthdays and document expiries. To ensure these alerts are sent automatically, you must set up the Laravel Scheduler.

#### A. Local Development
To run scheduled tasks locally while you are working, keep this command running in a separate terminal:
```bash
php artisan schedule:work
```

#### B. Live Server (Production)
On a live server, you must add a single Cron entry to your server. 

1. Open your server's crontab (usually `crontab -e` via SSH) or use the **Cron Jobs** section in CPanel.
2. Add the following line (replace `/path-to-your-project` with the actual path):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Queue Configuration (For background jobs)

This project offloads heavy tasks (like processing progressive tax calculations for large datasets) to background queues.

#### A. Configure Queue Driver (`.env`)
In your `.env` file, configure the connection type:
```env
QUEUE_CONNECTION=database
```
*For production with large datasets, it is highly recommended to use `redis`.*

#### B. Start Queue Worker
* **Local Development**: Keep this command running in a separate terminal window:
  ```bash
  php artisan queue:work
  ```
* **Production**: Set up a process supervisor (like **Supervisor** on Linux) to keep `php artisan queue:work` running continuously in the background.

### 9. Database Seeding (Optional)
If you want to populate the database with sample data:
```bash
php artisan db:seed
```

### 10. Default Credentials
Use the following credentials to access the admin panel:
- **Email:** admin@example.com
- **Password:** 12345678

### 11. Storage Symlink

If there is an existing `storage` folder inside the `public` directory, delete it first. Then, run the following command to create the symbolic link:

```bash
php artisan storage:link
```

## Running the Application

### Option A: Running Locally (Traditional)
Once the installation is complete, you can start the development server:
```bash
php artisan serve
```

### Option B: Running with Docker & MinIO (Recommended)

The Compose configuration runs PHP-FPM, Nginx, MySQL, the queue worker, the Laravel scheduler, and MinIO.

#### Docker prerequisites

- Docker Engine or Docker Desktop with BuildKit enabled
- Docker Compose v2 (`docker compose`)
- An SSH key with access to the private
  `innovity-Technologies-Inc/laravel-approval-engine` GitHub repository

#### 1. Configure GitHub SSH access

Composer installs the private package during the image build. The Dockerfile uses
BuildKit SSH forwarding, so the key remains on the host and is not copied into the
image.

Confirm that the host can access GitHub:

```bash
ssh -T git@github.com
```

Check whether the required key is loaded:

```bash
ssh-add -l
```

If it is not loaded, add it to the SSH agent:

```bash
ssh-add
```

This loads SSH keys from their standard locations. If the authorized key uses a
custom filename or location, pass its path to `ssh-add`. For example:

```bash
ssh-add ~/.ssh/id_ed25519
```

Replace the example path with the path to your authorized private key. Confirm
that the associated GitHub account has access to the
`innovity-Technologies-Inc` organization and the private
`laravel-approval-engine` repository. If the organization enforces SAML SSO,
authorize the SSH key for `innovity-Technologies-Inc` as well.

#### 2. Configure the environment

Create the local environment file if it does not exist:

```bash
cp .env.example .env
```

The Compose file configures container-specific database and MinIO hostnames.
Values such as `DB_DATABASE`, `DB_PASSWORD`, `MINIO_ROOT_USER`, and
`MINIO_ROOT_PASSWORD` can be overridden in `.env`.

#### 3. Build and start the stack

```bash
docker compose up -d --build
```

The first startup can take a few minutes. The application container waits for
MySQL, generates an application key if needed, runs migrations, and seeds an
empty database in the local environment.

Check container status and follow the application logs:

```bash
docker compose ps
docker compose logs -f app
```

#### 4. Access the services

- Application: `http://localhost`
- MinIO Console: `http://localhost:9001`
- Default MinIO credentials: `minioadmin` / `minioadmin`

Open the MinIO Console and create the following buckets:

- `hrms-dev`, matching the default `AWS_BUCKET`
- `hrms-prod`, for production use

Set the required bucket access policy for browser-accessible assets. Do not make
private documents public.

#### Common Docker commands

```bash
# Run an Artisan command
docker compose exec app php artisan about

# Run migrations
docker compose exec app php artisan migrate

# Clear & rebuild application cache
docker compose exec app php artisan optimize
docker compose exec app php artisan config:clear

# Run database seeders
docker compose exec app php artisan db:seed
docker compose exec app php artisan db:seed --class=ApprovalWorkflowSeeder

# Open interactive shell terminal inside app container
docker compose exec app bash

# Run single command via direct Docker CLI
docker exec -it hrms-app php artisan optimize

# Follow logs from every service
docker compose logs -f

# Stop the stack
docker compose down

# Rebuild after dependency or Dockerfile changes
docker compose build --no-cache
docker compose up -d
```

To also remove the MySQL and MinIO volumes, use
`docker compose down --volumes`. This permanently removes the local database and
object-storage data.

#### Build the application image without Compose

Forward the active SSH agent explicitly:

```bash
docker build --ssh default -t hrms-app .
```

The resulting image runs PHP-FPM on port `9000` and requires external database
and object-storage services plus the corresponding environment variables.

#### Private repository troubleshooting

If Composer reports `Failed to download innovity/laravel-approval-engine`,
`Permission denied (publickey)`, or a private GitHub archive returns `404`:

```bash
ssh-add -l
ssh -T git@github.com
docker compose --progress plain build --no-cache
```

Verify that:

- The loaded key has access to the private repository.
- The key is authorized for the GitHub organization when SSO is enforced.
- `SSH_AUTH_SOCK` is available in the shell running Docker.
- The build uses BuildKit; do not copy private SSH keys into the image or build
  context.

---

## 🏗️ Architectural Overview
- **Framework**: Laravel 12.
- **Pattern**: Service-Oriented Architecture (SOA).
- **Business Logic**: Centralized in `App\Services`.
- **Filtering**: `daiyanmozumder/laravel-flexsearch`.

## 🎨 Design & UI Standards
- **Framework**: Bootstrap 5.
- **Style**: Modern "Glassmorphism" aesthetic.
- **Dark Mode**: Supported via `[data-bs-theme=dark]`.

## 📦 Key Packages
- `spatie/browsershot`: PDF generation (Payslips/ID Cards).
- `maatwebsite/excel`: Excel imports/exports.
- `endroid/qr-code`: QR code generation.
