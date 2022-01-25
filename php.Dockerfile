# FROM microsoft/mssql-tools as mssql ##Depricated
FROM mcr.microsoft.com/mssql-tools as mssql
FROM php:7.4-fpm-alpine3.13

# map MSSQL drivers
COPY --from=mssql /opt/microsoft/ /opt/microsoft/
COPY --from=mssql /opt/mssql-tools/ /opt/mssql-tools/
COPY --from=mssql /usr/lib/libmsodbcsql-13.so /usr/lib/libmsodbcsql-13.so

# setup webroot
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# setup basic extentions
RUN apk update
RUN apk upgrade
RUN apk add --no-cache libzip-dev libpng-dev oniguruma-dev libxml2-dev
RUN docker-php-ext-install zip gd mysqli pdo_mysql soap


# mssql odbc for dabase connection
RUN curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_17.5.2.1-1_amd64.apk
RUN curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_17.5.2.1-1_amd64.apk
RUN apk add --allow-untrusted msodbcsql17_17.5.2.1-1_amd64.apk
RUN apk add --allow-untrusted mssql-tools_17.5.2.1-1_amd64.apk

# installl and compile
RUN set -xe \
    && apk add --no-cache --virtual .persistent-deps freetds unixodbc \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS unixodbc-dev freetds-dev \
    && docker-php-source extract \
    && docker-php-ext-install pdo_dblib \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv \
    && docker-php-source delete \
    && apk del .build-deps

#    && docker-php-ext-enable --ini-name 30-sqlsrv.ini sqlsrv \
#    && docker-php-ext-enable --ini-name 35-pdo_sqlsrv.ini pdo_sqlsrv \
# clean up
RUN apk del gcc g++ 
RUN rm -rf /var/cache/apk/*

# setup security
RUN addgroup -g 1000 wwwphp && adduser -G wwwphp -g wwwphp -s /bin/sh -D wwwphp
RUN mkdir -p /var/www/html
RUN chown wwwphp:wwwphp /var/www/html
ADD ./www.conf /usr/local/etc/php-fpm.d/www.conf