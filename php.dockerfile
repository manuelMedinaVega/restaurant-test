FROM php:8.2.28-fpm

ENV PHPGROUP=restaurant
ENV PHPUSER=restaurant

RUN addgroup ${PHPGROUP}
RUN adduser --ingroup ${PHPGROUP} --shell /bin/sh --disabled-password ${PHPUSER}

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/www/html/public

RUN docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]