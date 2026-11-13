# ==============================================================================
# C28 — Software & Cloud Engineering
# Multi-Stage Production Dockerfile (Node 20 + Composer 2 + PHP 8.2-FPM + Nginx)
# ==============================================================================

# ------------------------------------------------------------------------------
# Stage 1: Frontend Asset Compilation (Vite + Tailwind CSS v3.4)
# ------------------------------------------------------------------------------
FROM node:20-alpine AS frontend-assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY app/Filament ./app/Filament
RUN npm run build

# ------------------------------------------------------------------------------
# Stage 2: Deterministic PHP Production Dependencies
# ------------------------------------------------------------------------------
FROM composer:2 AS vendor-deps

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# ------------------------------------------------------------------------------
# Stage 3: Hardened Production Runtime (PHP 8.2-FPM Alpine + Nginx + Supervisor)
# ------------------------------------------------------------------------------
FROM php:8.2-fpm-alpine AS production-runtime

LABEL maintainer="C28 — Software & Cloud Engineering <engineering@c28studios.com>"
LABEL org.opencontainers.image.title="C28 — Software & Cloud Engineering"
LABEL org.opencontainers.image.description="Production Laravel 11 + Filament v3 Control Plane Container"

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        opcache

# Create non-root application user & group
RUN addgroup -g 1000 -S c28user \
    && adduser -u 1000 -S c28user -G c28user

WORKDIR /var/www/html

# Copy application source
COPY --chown=c28user:c28user . /var/www/html

# Copy deterministic vendor directory & compiled Vite assets
COPY --from=vendor-deps --chown=c28user:c28user /app/vendor /var/www/html/vendor
COPY --from=frontend-assets --chown=c28user:c28user /app/public/build /var/www/html/public/build

# Copy hardened Nginx, PHP OPcache, Supervisor, and entrypoint configs
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p \
        /var/www/html/storage/framework/cache \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/storage/logs \
        /var/www/html/bootstrap/cache \
        /run/nginx \
    && chown -R c28user:c28user \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/lib/nginx \
        /var/log/nginx \
        /run/nginx

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD curl --fail --silent http://127.0.0.1:8080/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

