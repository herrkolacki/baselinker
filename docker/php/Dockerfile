FROM php:8.3-fpm-alpine


RUN apk add --no-cache \
    git \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    linux-headers


RUN docker-php-ext-install \
    intl \
    zip \
    opcache \
    bcmath


RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/Baselinker