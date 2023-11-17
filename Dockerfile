FROM php:7.3-apache
MAINTAINER Yusup Maulana <yusup.maulana@booksbeyond.co.id>

RUN apt-get update
RUN apt-get install -y libzip-dev libpng-dev zip libjpeg-dev zlib1g-dev libwebp-dev libjpeg62-turbo-dev libpng-dev libicu-dev libxpm-dev libfreetype6-dev nano g++

RUN docker-php-ext-install mysqli zip intl

RUN docker-php-ext-configure gd --with-freetype-dir=/usr --with-jpeg-dir=/usr --with-png-dir=/usr && docker-php-ext-install gd

RUN rm -Rf /var/www/html

COPY app /var/www/app
COPY public /var/www/html
COPY system /var/www/system
COPY writable /var/www/writable
COPY .env /var/www/.env

COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

ADD start.sh /bootstrap/start.sh

RUN chmod 755 /bootstrap/start.sh

WORKDIR /var/www

RUN chown -R www-data:www-data app
RUN chown -R www-data:www-data html
RUN chown -R www-data:www-data system
RUN chown -R www-data:www-data writable

RUN chmod -R 755 app
RUN chmod -R 755 html
RUN chmod -R 755 system
RUN chmod -R 755 writable

RUN a2enmod rewrite

ENTRYPOINT ["/bootstrap/start.sh"]
