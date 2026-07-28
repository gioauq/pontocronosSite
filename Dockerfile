# syntax=docker/dockerfile:1

FROM php:8.2-fpm-alpine

RUN apk add --no-cache sqlite-libs \
    && apk add --no-cache --virtual .build-deps sqlite-dev \
    && docker-php-ext-install pdo_sqlite \
    && apk del .build-deps

WORKDIR /var/www/html

COPY php/security.ini /usr/local/etc/php/conf.d/zz-security.ini

COPY . .

RUN addgroup -g 1000 -S www \
    && adduser -u 1000 -S www -G www \
    && mkdir -p cronossite/cronos/data \
    && chown -R www:www /var/www/html

USER www

CMD ["php-fpm"]
