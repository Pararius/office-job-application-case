FROM php:7.4-cli-alpine as php

RUN apk add --no-cache \
    g++ \
    curl \
    git \
    libzip-dev

RUN docker-php-ext-install \
    pcntl \
    posix \
    zip

RUN apk add --virtual .build-dependencies --no-cache \
    autoconf \
    gcc \
    libc-dev \
    linux-headers \
    make \
    && pecl channel-update pecl.php.net \
    && pecl install grpc \
    && docker-php-ext-enable grpc \
    && apk del .build-dependencies

ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
COPY --from=composer:1.9.3 /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo

WORKDIR /app
