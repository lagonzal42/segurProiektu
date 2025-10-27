FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
COPY ./app /var/www/html/
