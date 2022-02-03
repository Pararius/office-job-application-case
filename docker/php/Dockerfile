FROM eu.gcr.io/real-estate-classifieds/shared/php:8.1 as php

ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
