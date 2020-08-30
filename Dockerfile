FROM php:7.4-fpm
RUN docker-php-ext-install pdo_mysql


RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

RUN apt-get update
RUN apt-get install -y git

RUN apt-get install -y nginx

COPY ./www/ /app/
COPY ./site.conf /etc/nginx/conf.d/default.conf

WORKDIR /app

RUN composer install --prefer-source --no-interaction
