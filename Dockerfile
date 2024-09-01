FROM composer:2.7.8 AS composer-src
FROM mlocati/php-extension-installer:2.4 AS extension-installer

FROM php:8.3.11-alpine AS php
COPY --from=extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions \
      grpc \
      intl \
    ;

ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
COPY --from=composer-src /usr/bin/composer /usr/bin/composer

LABEL org.opencontainers.image.source="https://github.com/Pararius/office-job-application-case"

WORKDIR /app
