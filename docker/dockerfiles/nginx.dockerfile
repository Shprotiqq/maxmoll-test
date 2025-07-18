FROM nginx:stable-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

RUN delgroup dialout

RUN addgroup -g ${GID} --system maxmoll_test
RUN adduser -G maxmoll_test --system -D -s /bin/sh -u ${UID} maxmoll_test
RUN sed -i "s/user  nginx/user maxmoll_test/g" /etc/nginx/nginx.conf
