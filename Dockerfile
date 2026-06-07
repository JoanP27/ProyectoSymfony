FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    bash

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public/"]