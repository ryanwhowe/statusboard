FROM php:7.2-fpm
RUN docker-php-ext-install pdo_mysql
RUN pecl install apcu
RUN apt-get update -y && \
apt-get install -y \
zlib1g-dev unzip gnupg \
&& curl -sL https://deb.nodesource.com/setup_11.x | bash \
&& apt-get install nodejs -yq

RUN docker-php-ext-install opcache
RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_CACHE_DIR=/tmp
WORKDIR /usr/src/app

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin