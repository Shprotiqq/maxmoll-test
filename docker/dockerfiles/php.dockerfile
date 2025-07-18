FROM php:8.4-fpm-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN delgroup dialout

RUN addgroup -g ${GID} --system maxmoll_test
RUN adduser -G maxmoll_test --system -D -s /bin/sh -u ${UID} maxmoll_test

RUN sed -i "s/user = www-data/user = maxmoll_test/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = maxmoll_test/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install pdo pdo_mysql

RUN echo "memory_limit = 1024M" >> /usr/local/etc/php/conf.d/docker-fpm.ini
RUN echo "max_input_vars = 10000" >> /usr/local/etc/php/conf.d/docker-fpm.ini
RUN echo "max_multipart_body_parts = 10000" >> /usr/local/etc/php/conf.d/docker-fpm.ini
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/docker-fpm.ini
RUN echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/docker-fpm.ini


#XDebug
RUN apk update && \
    apk upgrade && \
    apk add --no-cache linux-headers && \
    apk add --no-cache --virtual .xdebug $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .xdebug

USER maxmoll_test

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
