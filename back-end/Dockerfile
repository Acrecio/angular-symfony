FROM php:7.4-fpm

# Install Composer
RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version

ADD . /var/www
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpq-dev \
        libpng-dev

RUN pecl install mcrypt-1.0.3

RUN docker-php-ext-enable mcrypt \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN usermod -u 1000 www-data \
    && mkdir -p /var/www/app/cache \
    && mkdir -p /var/www/app/logs \
    && chmod -R 777 /var/www/app/cache \
    && chmod -R 777 /var/www/app/logs

EXPOSE 9000
CMD ["php-fpm"]
