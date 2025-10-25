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
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_pgsql zip \
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
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && apk del $PHPIZE_DEPS
WORKDIR /var/www/html

FROM nginx:alpine AS web
WORKDIR /var/www/html
COPY --from=php /usr/local/etc/php-fpm.d/ /usr/local/etc/php-fpm.d/
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY . .

# Nginx conf
RUN echo "server {\n  listen 80;\n  server_name _;\n  root /var/www/html/public;\n  index index.php index.html;\n  location / { try_files $uri $uri/ /index.php?$query_string; }\n  location ~ \\.(php|phar)$ {\n    include fastcgi_params;\n    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n    fastcgi_pass php:9000;\n  }\n}\n" > /etc/nginx/conf.d/default.conf

# 3) Final: Use docker-compose to run php+nginx