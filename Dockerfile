FROM php:apache

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libexif-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql exif mbstring

COPY ./config/php.ini /usr/local/etc/php/
COPY . /var/www/html

ADD run.sh /
RUN chmod 755 /run.sh

CMD ["/run.sh"]