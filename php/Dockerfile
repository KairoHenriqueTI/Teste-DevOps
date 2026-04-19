FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       default-mysql-client \
       libzip-dev \
       unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
