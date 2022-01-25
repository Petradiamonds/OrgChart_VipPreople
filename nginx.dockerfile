FROM nginx:stable-alpine

ADD ./nginx/nginx.conf /etc/nginx/nginx.conf
ADD ./nginx/default.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p /var/www/html

RUN addgroup -g 1000 wwwphp && adduser -G wwwphp -g wwwphp -s /bin/sh -D wwwphp

RUN chown wwwphp:wwwphp /var/www/html