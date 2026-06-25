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

    # Seeder Lock
    if [ ! -f "storage/logs/seeded.lock" ]; then
        echo "Running seeders for the first time..."
        php artisan db:seed --no-interaction
        php artisan view:cache
        touch storage/logs/seeded.lock
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

# 7. Execution
echo "Starting application..."
exec "$@"
