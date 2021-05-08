FROM yiisoftware/yii2-php:7.1-apache
MAINTAINER pizaini <github.com/pizaini>

#created web root dir
RUN mkdir /app/web

#Copy all file
COPY . /app/web

#copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/php.ini

#change workdir
WORKDIR /app/web

#install rar extension

#run composer install
RUN composer install
