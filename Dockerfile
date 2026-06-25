FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    bash \
    curl \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions using mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql bcmath zip gd opcache intl mbstring curl xml

# Configure PHP production defaults
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Ensure entrypoint script is executable
RUN chmod +x /var/www/html/docker/entrypoint.sh

# Define entrypoint
ENTRYPOINT ["bash", "/var/www/html/docker/entrypoint.sh"]

CMD ["php-fpm"]
