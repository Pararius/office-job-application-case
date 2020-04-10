FROM php:7.4.4-cli-alpine3.10 AS php-cli

# add proper iconv support
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN apk add --no-cache \
    postgresql-dev \
    g++ \
    icu-dev \
    libzip-dev \
    rabbitmq-c-dev \
    zlib-dev

RUN docker-php-ext-install \
    bcmath \
    sockets \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    pcntl \
    posix \
    zip

RUN apk add --virtual .build-dependencies --no-cache \
    autoconf \
    gcc \
    git \
    libc-dev \
    linux-headers \
    make \
    && pecl channel-update pecl.php.net \
    # compile
    # install extensions
    && pecl install grpc \
    && docker-php-ext-enable grpc \
    # cleanup
    && apk del .build-dependencies

COPY conf.d/php.ini $PHP_INI_DIR

WORKDIR /app

FROM php-cli AS composer

RUN apk add --no-cache \
    curl \
    git

ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer

COPY --from=composer:1.9.3 /usr/bin/composer /usr/bin/composer

RUN composer global require hirak/prestissimo
