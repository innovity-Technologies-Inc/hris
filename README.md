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

### 5. Application Optimization

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

### 7. Database Seeding (Optional)
If you want to populate the database with sample data:
```bash
php artisan db:seed
```

### 8. Default Credentials
Use the following credentials to access the admin panel:
- **Email:** admin@example.com
- **Password:** 12345678

### 9. Storage Symlink

If there is an existing `storage` folder inside the `public` directory, delete it first. Then, run the following command to create the symbolic link:

```bash
php artisan storage:link
```

## Running the Application

Once the installation is complete, you can start the development server:

```bash
php artisan serve
```

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
