FROM eu.gcr.io/real-estate-classifieds/shared/php:7.4 as php

ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
COPY --from=composer:1.9.3 /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo

WORKDIR /app
