FROM php:7.2.2-apache
RUN echo "ServerTokens Prod\nServerSignature Off" >> /etc/apache2/conf-available/security.conf && \
    a2enconf security
RUN a2enmod headers && \
    echo 'Header always set Server "SegurServer"' >> /etc/apache2/conf-enabled/security.conf
    
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
COPY ./app /var/www/html/
