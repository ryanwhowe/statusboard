FROM php:7.2-fpm
RUN docker-php-ext-install pdo_mysql
RUN pecl install apcu
RUN pecl install xdebug
RUN apt-get update -yq && \
apt-get install -yq \
zlib1g-dev unzip gnupg vim \
&& curl -sL https://deb.nodesource.com/setup_12.x | bash \
&& apt-get install nodejs -yq

RUN docker-php-ext-install opcache
RUN docker-php-ext-install zip
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-enable apcu
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_CACHE_DIR=/tmp

RUN npm config set cache /tmp --global

WORKDIR /usr/src/app

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin