FROM mlocati/php-extension-installer:1.5 AS extension-installer
FROM php:8.1.6-fpm-alpine3.15 AS php
COPY --from=extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions \
      grpc-1.44.0 \
      intl \
    ;

ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

LABEL org.opencontainers.image.source="https://github.com/Pararius/casco-job-interview"

WORKDIR /app
