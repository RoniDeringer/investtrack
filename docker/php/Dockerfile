FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# System deps for common PHP extensions used by Laravel + Postgres + Redis
RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    zip \
    unzip

RUN docker-php-ext-install -j"$(nproc)" \
    bcmath \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo_pgsql \
    zip

# Redis (phpredis) via PECL
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-investtrack.ini
