FROM php:7.3-apache
WORKDIR /var/www/html
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y build-essential libpng-dev libjpeg62-turbo-dev libfreetype6-dev locales zip \
    jpegoptim optipng pngquant gifsicle nano mc unzip git curl wget libmcrypt-dev libzip-dev libxml2-dev
#RUN pecl install mcrypt-1.0.2
#RUN docker-php-ext-enable mcrypt
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl xml
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd
RUN a2enmod rewrite
ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && composer global require hirak/prestissimo --no-plugins --no-scripts \
    && rm -rf /root/.composer/cache
RUN unlink /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime
RUN usermod -u 1000 www-data && chown -R www-data:www-data /var/www