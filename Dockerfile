# Multi-stage build for Laravel 11 app (PHP 8.2 + Nginx)

# 1) Build stage: composer and node
FROM composer:2 AS vendor
WORKDIR /app
RUN apk add --no-cache \
        $PHPIZE_DEPS \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
        postgresql-dev \
        mariadb-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_pgsql zip opcache \
    && apk del $PHPIZE_DEPS
COPY composer.json composer.lock .
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
RUN npm install --silent
COPY resources ./resources
COPY vite.config.js .
RUN npm run build

# 2) Runtime stage: php-fpm + nginx
FROM php:8.2-fpm-alpine AS php
RUN apk add --no-cache $PHPIZE_DEPS \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql opcache \
    && apk del $PHPIZE_DEPS

# PHP configuration
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY php.ini-production /usr/local/etc/php/php.ini

# Enable OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=16000" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

FROM nginx:alpine AS web
WORKDIR /var/www/html
COPY --from=php /usr/local/etc/php-fpm.d/ /usr/local/etc/php-fpm.d/
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY . .

# Nginx conf
COPY nginx.conf /etc/nginx/conf.d/default.conf

# 3) Final: Use docker-compose to run php+nginx