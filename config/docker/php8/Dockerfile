FROM php:8.0-fpm-alpine

COPY --from=composer:2.0 /usr/bin/composer /usr/local/bin/composer

ENV EXT_AMQP_VERSION=master

RUN apk add --no-cache \
        ca-certificates \
        icu-libs \
        git \
        unzip \
        zlib-dev \
        libxml2-dev \
        # gd
        freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
        openssh-client && \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev

RUN docker-php-source extract \
    && apk -Uu add git rabbitmq-c-dev \
    && git clone --branch $EXT_AMQP_VERSION --depth 1 https://github.com/php-amqp/php-amqp.git /usr/src/php/ext/amqp \
    && cd /usr/src/php/ext/amqp && git submodule update --init \
    && docker-php-ext-install amqp

RUN docker-php-ext-install \
            intl \
            pcntl \
            pdo_mysql

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

ARG XDEBUG_VERSION=3.0.1
RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-$XDEBUG_VERSION; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps; \
    apk add gosu --update --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing/ --allow-untrusted && \
    addgroup bar && \
    adduser -D -h /home -s /bin/sh -G bar foo