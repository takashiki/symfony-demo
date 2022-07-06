# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target


# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION=8.1

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine3.16 AS symfony_php

# "fix" the well-known net issue in china
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories

# persistent / runtime deps
RUN apk add --no-cache \
	acl \
	fcgi \
	file \
	gettext \
	git \
	;

ARG APCU_VERSION=5.1.21
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
	$PHPIZE_DEPS \
	icu-data-full \
	icu-dev \
	libzip-dev \
	zlib-dev \
	libpng-dev \
	freetype-dev \
	libjpeg-turbo-dev \
	libxslt-dev \
	oniguruma-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
	intl \
	zip \
	; \
	pecl install \
	apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-configure gd --with-jpeg --with-freetype; \
	docker-php-ext-install pdo pdo_mysql zip xsl gd intl opcache exif mbstring; \
	docker-php-ext-enable apcu opcache;

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini

COPY php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

VOLUME /var/run/php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
	&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
	&& "date"

CMD ["php-fpm"]
EXPOSE 9000
