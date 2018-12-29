FROM php:7-apache

RUN apt-get update -y && apt-get install -y libpng-dev curl libcurl4-openssl-dev mc

# generate self-sign ssl cert
RUN openssl req -new -newkey rsa:4096 -days 3650 -nodes -x509 -subj "/C=UK/ST=x/L=y/O=LxdManager/CN=lxdmanager.local" -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem

RUN docker-php-ext-install pdo pdo_mysql gd curl

# apache set
RUN a2enmod rewrite && a2enmod ssl && service apache2 restart
