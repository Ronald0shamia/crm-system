FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    ca-certificates \
    openssl \
    && update-ca-certificates

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    intl \
    gd

# SSL config
RUN echo "openssl.cafile=/etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/conf.d/docker-php-ext-openssl.ini \
    && echo "curl.cainfo=/etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/conf.d/docker-php-ext-curl.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www