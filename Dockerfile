FROM yiisoftware/yii2-php:5.6-apache
MAINTAINER pizaini <github.com/pizaini>

ARG GITHUB_TOKEN

#created web root dir
RUN mkdir /app/web

#Copy all file
COPY . /app/web

#copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/php.ini

#change workdir to document root
WORKDIR /app/web

#install rar extension

##Configure composer##
#install composer v1
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --version=1.10.22
RUN composer self-update --1

RUN composer config github-oauth.github.com GITHUB_TOKEN
RUN composer global require fxp/composer-asset-plugin

#run composer install
RUN composer install --no-dev

#Rename dir
RUN mv /app/web/vendor/bower-asset /app/web/vendor/bower
RUN mv /app/web/vendor/bower/jquery-querybuilder /app/web/vendor/bower/jQuery-QueryBuilder

#change permissions and owner
RUN chmod 755 -R /app/web
RUN chown www-data:www-data -R /app/web