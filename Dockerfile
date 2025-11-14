# Usa PHP 7.3 con Apache
FROM php:7.3-apache

# Configuración de seguridad de Apache
RUN echo "ServerTokens Prod\nServerSignature Off" >> /etc/apache2/conf-available/security.conf && \
    a2enconf security

# Activa el módulo headers y configura cabeceras personalizadas
RUN a2enmod headers && \
    echo 'Header always set Server "SegurServer"' >> /etc/apache2/conf-enabled/security.conf

# Instala extensiones necesarias
RUN docker-php-ext-install mysqli

# Activa mod_rewrite y permite .htaccess
RUN a2enmod rewrite && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copia el código de la aplicación
COPY ./app /var/www/html/
