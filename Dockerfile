# ======================
# Frontend Build
# ======================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

# ======================
# Composer Build
# ======================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

# Menambahkan --ignore-platform-reqs agar composer mengabaikan 
# pengecekan ekstensi PHP di dalam container build ini.
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --ignore-platform-reqs

COPY . .

RUN composer dump-autoload --optimize --ignore-platform-reqs

# ======================
# Production Environment
# ======================
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Install package sistem yang dibutuhkan Alpine
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    unzip \
    zip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev

# Konfigurasi ekstensi GD untuk PhpSpreadsheet / Filament
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Install ekstensi PHP ke dalam sistem production
RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    intl \
    zip \
    gd \
    mbstring

# Copy resource dari build stage sebelumnya
COPY --from=composer /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Copy konfigurasi Docker pendukung
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh

# Setup direktori storage dan cache Laravel
RUN mkdir -p \
    storage/logs \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views

# Set permission agar web server bisa menulis log & cache
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]