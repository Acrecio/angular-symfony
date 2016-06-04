FROM php:5-fpm

ADD . /var/www
WORKDIR /var/www

RUN usermod -u 1000 www-data
RUN mkdir -p /var/www/app/cache
RUN mkdir -p /var/www/app/logs
RUN chmod -R 777 /var/www/app/cache
RUN chmod -R 777 /var/www/app/logs

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql

EXPOSE 9000
CMD ["php-fpm"]
