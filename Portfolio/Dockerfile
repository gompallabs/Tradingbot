ARG PHP_VERSION=8.2
ARG INSTALL_DIR=app
ARG NGINX_VERSION=1.20

FROM alpine:3.18 as app-builder
ARG APP_ENV
ARG APP_DIR
ARG INSTALL_DIR

WORKDIR /srv/app

COPY behat.yml.dist composer.json composer.lock symfony.lock .env ./
COPY bin ./bin
COPY config ./config
COPY public ./public
COPY src ./src
COPY tests ./tests
COPY translations ./translations

FROM php:${PHP_VERSION}-fpm-alpine AS app_php
ARG GID=1000
ARG UID=1000
ARG TZ
ENV TZ=${TZ}
ENV GID="${GID}"
ENV UID="${UID}"
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk add --no-cache --update linux-headers \
    acl \
    fcgi \
    file \
    gettext \
    icu-dev \
    git \
    rabbitmq-c \
    rabbitmq-c-dev \
    gnu-libiconv \
    libzip-dev \
    libsodium-dev \
    tzdata \
;

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN set -o -eux; \
	apk add --no-cache --virtual .build-deps \
		"$PHPIZE_DEPS" \
		zlib-dev \
	; \
	\
	install-php-extensions http intl zip opcache redis amqp pgsql pdo_pgsql \
    ; \
	docker-php-ext-enable \
        amqp \
		opcache \
        intl \
        redis \
        zip \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps;

COPY --from=app-builder /srv /srv

# fpm config
COPY .docker/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY .docker/php-fpm.d/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
COPY .docker/php-fpm.d/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY .docker/php-fpm.d/security-checker-install.sh /usr/local/bin/security-checker-install.sh
COPY .docker/php-fpm.d/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh


WORKDIR /srv/app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -s "$PHP_INI_DIR"/php.ini-production "$PHP_INI_DIR"/php.ini ; \
    set -eux; \
    rm -rf var/cache var/log /composer ; \
	mkdir -p var/cache var/log /composer; \
	composer install --prefer-dist --no-progress --no-scripts --no-interaction; \
	composer dump-autoload --classmap-authoritative; \
	composer symfony:dump-env dev; \
	composer run-script --no-dev post-install-cmd; \
    chmod +x /usr/local/bin/docker-entrypoint.sh  /usr/local/bin/docker-healthcheck; \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && apk del tzdata; \
    sync

VOLUME /srv/app/var

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm"]

FROM nginx:${NGINX_VERSION}-alpine AS symfony_nginx
ARG NGINX_VERSION
ARG INSTALL_DIR
ARG NGINX_CONF_DIR

WORKDIR /srv/${INSTALL_DIR}

COPY --from=app-builder /srv/${INSTALL_DIR}/public /srv/${INSTALL_DIR}/public
COPY ${NGINX_CONF_DIR}/conf.d/default.conf /etc/nginx/conf.d/default.conf
