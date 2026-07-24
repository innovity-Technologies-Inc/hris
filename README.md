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
This project includes a `docker-compose.yml` file to orchestrate containers for PHP-FPM, Nginx, MySQL, Queue workers, Scheduler, and MinIO storage.

1. **Start the containers**:
   ```bash
   docker-compose up -d --build
   ```
2. **Access the Application**:
   * App URL: `http://localhost`
   * MinIO Console: `http://localhost:9001` (Credentials: `minioadmin` / `minioadmin`)
3. **Configure MinIO Buckets**:
   * Open the MinIO Console at `http://localhost:9001`.
   * Go to **Buckets** -> **Create Bucket** and create two buckets: `hrms-dev` (for development, matching `AWS_BUCKET` in your `.env`) and `hrms-prod` (for production).
   * Set the **Access Policy** for both buckets to `Public` to allow browser access to assets.

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
