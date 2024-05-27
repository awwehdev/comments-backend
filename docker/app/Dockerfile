FROM php:8.3-fpm-alpine

ARG WWWGROUP=1000

WORKDIR /app

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk add --no-cache --virtual .dd-build-deps libpng-dev freetype-dev libjpeg-turbo-dev libxml2-dev libzip-dev oniguruma-dev $PHPIZE_DEPS \
   && docker-php-ext-install mbstring pdo_mysql zip \
   && docker-php-ext-install opcache bcmath

RUN pecl install redis
RUN docker-php-ext-enable redis

ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev
RUN apk add --no-cache --update libmemcached-libs zlib memcached
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS
RUN yes '' | pecl install -f memcached \
    && docker-php-ext-enable memcached

RUN apk add libxml2-dev libzip-dev libpq-dev \
    libpng-dev libwebp-dev libpng-dev libxpm-dev libssh-dev libmcrypt-dev \
    gcc make autoconf libc-dev

RUN docker-php-ext-configure gd \
    --with-webp \
    --with-jpeg \
    --with-xpm \
    --with-freetype

RUN docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install -j$(nproc) opcache
RUN docker-php-ext-enable opcache

RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN docker-php-ext-install -j$(nproc) pcntl
RUN docker-php-ext-enable pcntl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add mysql-client bash nginx
RUN apk add lsb-release net-tools vim gnupg curl ca-certificates htop sudo zip unzip git supervisor wget openssl cronie

RUN apk add --no-cache --virtual .deps imagemagick imagemagick-libs imagemagick-dev

RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/7088edc353f53c4bc644573a79cdcd67a726ae16.tar.gz \
    && tar --strip-components=1 -xf /tmp/imagick.tar.gz \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && rm -rf /tmp/*

RUN apk add libcurl curl-dev

RUN docker-php-ext-install -j$(nproc) curl

RUN apk add imap-dev krb5-dev
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && docker-php-ext-enable imap

RUN docker-php-ext-install -j$(nproc) mbstring

RUN docker-php-ext-install -j$(nproc) xml
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) bcmath

RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) bz2

RUN apk add --no-cache gettext-dev

RUN docker-php-ext-install -j$(nproc) gettext
RUN docker-php-ext-install -j$(nproc) calendar

# RUN apk add --update linux-headers
# RUN pecl install xdebug
# RUN docker-php-ext-enable xdebug

EXPOSE 80
EXPOSE 443

CMD ["/bin/bash", "-c", "php-fpm && nginx -g \"daemon off;\""]

ENTRYPOINT ["bash", "/usr/local/bin/entrypoint.sh"]
