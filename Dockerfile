FROM yiisoftware/yii2-php:5.6-apache
MAINTAINER pizaini <github.com/pizaini>

RUN apt-get update && apt-get install unzip -y

#created web root dir
RUN mkdir /app/web

#Copy all file
COPY ./src/ /app/web/

#copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/php.ini

#Container temp dir
RUN mkdir /docker
COPY ./docker/uploaded_files.zip /docker/
COPY ./docker/vendor/ /docker/

COPY docker/run.sh /run.sh
RUN chmod +x /run.sh
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

#INSTALL rar extension
RUN curl http://pecl.php.net/get/rar-3.0.2.tgz -o rar-3.0.2.tgz
RUN gunzip rar-3.0.2.tgz
RUN tar -xvf rar-3.0.2.tar
WORKDIR rar-3.0.2
RUN phpize
RUN ./configure && make && make install
RUN sed -i 's/;   extension=msql.so/extension=\/usr\/lib\/php5\/20121212\/rar.so/g' /usr/local/etc/php/php.ini

#extract vendor
RUN unzip /docker/vendor.zip -d /app/web/

#change workdir to document root
WORKDIR /app/web

#change directory owner
RUN chown www-data:www-data -R /app/web

CMD ["/run.sh"]