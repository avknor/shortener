FROM php:7.2-fpm

RUN apt-get update \
 && apt-get install -y vim git zlib1g-dev libzip-dev zlib1g-dev libicu-dev libpq-dev unzip g++ \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl mysqli pdo pdo_mysql zip \
 && docker-php-ext-enable pdo_mysql \
# && pecl install xdebug \
# && docker-php-ext-enable xdebug \
# && echo 'xdebug.remote_enable=on' >> /usr/local/etc/php/conf.d/xdebug.ini \
# && echo 'xdebug.remote_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini \
# && echo 'xdebug.remote_port=9000' >>  /usr/local/etc/php/conf.d/xdebug.ini \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
