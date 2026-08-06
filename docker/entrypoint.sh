#!/bin/bash
set -e

# 1. Env Check
if [ ! -f ".env" ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env
fi

# 2. Database Wait
echo "Waiting for database to be ready..."
php -r "
\$host = getenv('DB_HOST') ?: 'db';
\$port = getenv('DB_PORT') ?: '3306';
\$user = getenv('DB_USERNAME') ?: 'hrms_user';
\$pass = getenv('DB_PASSWORD') ?: 'secret';
\$db = getenv('DB_DATABASE') ?: 'hrms';
\$maxTries = 30;
for (\$i = 0; \$i < \$maxTries; \$i++) {
    try {
        new PDO(\"mysql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass);
        echo \"Database is ready!\n\";
        exit(0);
    } catch (Exception \$e) {
        echo \"Waiting for database... (\$i/\$maxTries)\n\";
        sleep(2);
    }
}
echo \"Database connection failed.\n\";
exit(1);
"

# 3. Environment Split
if [ "$APP_ENV" = "local" ]; then
    echo "Running in LOCAL environment..."
    
    # Crucial Caching Clear FIRST
    echo "Clearing caches manually to avoid boot crashes..."
    rm -f bootstrap/cache/*.php
    php artisan config:clear

    # 4. Local Command Serialization
    if [ ! -d "vendor" ]; then
        echo "Running composer install..."
        composer install --no-interaction
    fi

    echo "Checking application key..."
    if ! grep -q "APP_KEY=base64:" .env; then
        echo "Generating application key..."
        php artisan key:generate --no-interaction
    fi

    echo "Running migrations..."
    php artisan migrate --no-interaction

    echo "Clearing other caches..."
    php artisan cache:clear
    php artisan route:clear

    # Check if database is already seeded by checking users count
    DB_USER_COUNT=$(php -r "
        require 'vendor/autoload.php';
        \$app = require_once 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            echo Illuminate\Support\Facades\DB::table('users')->count();
        } catch (Exception \$e) {
            echo 0;
        }
    ")

    if [ -z "$DB_USER_COUNT" ] || [ "$DB_USER_COUNT" -eq 0 ]; then
        echo "Database is empty (users count: $DB_USER_COUNT). Running seeders..."
        php artisan db:seed --no-interaction
        php artisan view:cache
        touch storage/logs/seeded.lock
    else
        echo "Database is already seeded ($DB_USER_COUNT users found). Skipping seeding."
    fi
else
    echo "Running in PRODUCTION environment..."
    
    # Crucial Caching Clear FIRST
    echo "Clearing caches manually to avoid boot crashes..."
    rm -f bootstrap/cache/*.php
    php artisan config:clear

    # 5. Production Command Serialization
    echo "Checking application key..."
    if ! grep -q "APP_KEY=base64:" .env; then
        echo "Generating application key..."
        php artisan key:generate --force --no-interaction || php artisan key:generate
    fi

    echo "Running migrations (force)..."
    php artisan migrate --force --no-interaction

    echo "Caching configuration, routes, and views..."
    php artisan cache:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# 6. Permissions
echo "Setting permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure Chromium temp directories are writable by www-data
mkdir -p /tmp/.chromium
chown -R www-data:www-data /tmp/.chromium
chmod -R 775 /tmp/.chromium

# 7. Execution
echo "Starting application..."
exec "$@"
