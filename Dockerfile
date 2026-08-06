# syntax=docker/dockerfile:1

# ==========================================
# Stage 1: PHP Application Base
# ==========================================
FROM php:8.3-fpm AS app-base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    bash \
    curl \
    zip \
    unzip \
    git \
    openssh-client \
    nodejs \
    npm \
    chromium \
    && rm -rf /var/lib/apt/lists/*

# Set default environment variables for Spatie Browsershot/Chromium execution
ENV BROWSERSHOT_CHROME_PATH=/usr/bin/chromium
ENV HOME=/tmp
ENV XDG_CONFIG_HOME=/tmp/.chromium
ENV XDG_CACHE_HOME=/tmp/.chromium

# Trust GitHub's SSH host during private repository clones
RUN mkdir -p /root/.ssh && \
    chmod 700 /root/.ssh && \
    ssh-keyscan -H github.com >> /root/.ssh/known_hosts

# Install official PHP extension installer helper
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install PHP extensions required by the project (Redis removed)
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql bcmath zip gd opcache intl mbstring curl xml

# Configure PHP production defaults
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ==========================================
# Stage 2: Production Release Build
# ==========================================
FROM app-base AS production

# Copy project files
COPY . .

# Install production dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN --mount=type=ssh \
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Setup entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
