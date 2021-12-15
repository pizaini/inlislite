FROM yiisoftware/yii2-php:5.6-apache
MAINTAINER pizaini <github.com/pizaini>

#created web root dir
RUN mkdir /app/web

#Copy all file
COPY --chown=www-data:www-data ./src/ /app/web/

#copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/php.ini

#Container temp dir
RUN mkdir /docker
COPY ./docker/uploaded_files.tar.gz /docker/

#Add vendor
RUN mkdir /app/web/vendor
ADD --chown=www-data:www-data docker/vendor/vendor1.tar.gz /app/web/vendor/
ADD --chown=www-data:www-data docker/vendor/vendor2.tar.gz /app/web/vendor/

# INSTALL rar extension
WORKDIR /
RUN curl http://pecl.php.net/get/rar-3.0.2.tgz -o rar-3.0.2.tgz \
    && gunzip rar-3.0.2.tgz \
    && tar -xvf rar-3.0.2.tar \
    && rm rar-3.0.2.tar

WORKDIR rar-3.0.2
RUN phpize \
    && ./configure \
    && make && make install \
    && sed -i 's/;   extension=msql.so/extension=\/usr\/lib\/php5\/20121212\/rar.so/g' /usr/local/etc/php/php.ini \
    && rm -rf /rar-3.0.2

COPY ./docker/run.sh /run.sh
RUN chmod +x /run.sh
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

#change workdir to document root
WORKDIR /app/web
CMD ["/run.sh"]