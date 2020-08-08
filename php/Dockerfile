FROM php:7.2-fpm

RUN apt-get update \
  && apt-get install -y \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libxslt1-dev \
    libmemcached-dev

RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-webp-dir=/usr/include/;\
    docker-php-ext-install \
    opcache \
    bcmath \
    gd \
    intl \
    mbstring \
    pdo_mysql \
    soap \
    xsl \
    zip

RUN apt-get update && apt-get install -y libpng-dev
RUN apt update && apt install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    &&docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \  
    && docker-php-ext-install gd
    
RUN docker-php-ext-install sockets
#Install nano
RUN apt-get update && apt-get install nano

#Install composer
RUN cd ~ && curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN HASH=544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061
RUN cd ~ && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

#XDEBUG
ARG WITH_XDEBUG=false
 
RUN if [ $WITH_XDEBUG = "true" ] ; then \
        pecl install xdebug; \
        docker-php-ext-enable xdebug; \
        echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi ;


RUN echo 'memory_limit = 2G\n\
max_execution_time = 1800\n\
zlib.output_compression = On' >> /usr/local/etc/php/php.ini

RUN composer require php-amqplib/php-amqplib

WORKDIR /var/www/html