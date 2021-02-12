FROM php:7.4-apache

#install system modules
RUN apt-get update && apt-get install \
        curl \
        git \
        unzip \
        nano \
        libzip-dev \
        -y\
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

#install php modules
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install zip && \
    docker-php-ext-install mysqli && \
    a2enmod rewrite && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

#configure xdebug settings
ADD ./xdebug.ini /usr/local/etc/php/conf.d

RUN APACHE_SERVER_NAME=${APACHE_SERVER_NAME}
