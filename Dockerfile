FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        gnupg \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libpq-dev \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip gd mbstring \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && if [ -f package-lock.json ]; then npm ci; else npm install; fi \
    && npm run build \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000

CMD ["php-fpm"]
