FROM yiisoftware/yii2-php:5.6-apache
MAINTAINER pizaini <github.com/pizaini>

#created web root dir
RUN mkdir /app/web

#Copy all file
COPY . /app/web

#copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/php.ini

#change workdir to document root
WORKDIR /app/web

#install rar extension

#run composer install
RUN composer install

#change permission
RUN chmod 755 -R /app/web