FROM php:7.3-apache

# Installiere GD und Cron
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    inotify-tools \
    imagemagick \
    supervisor \
    # Entferne die explizite --with-freetype und --with-jpeg Konfiguration
    # und installiere gd direkt. Dies funktioniert oft besser bei älteren Images.
    && docker-php-ext-install gd

# Apache Rewrite aktivieren
RUN a2enmod rewrite

# imagemagick enable pdf
RUN sed -i 's|<policy domain="coder" rights="none" pattern="PDF" />|<!-- <policy domain="coder" rights="none" pattern="PDF" /> -->|' /etc/ImageMagick-6/policy.xml

# Apache root setzen & Rechte
WORKDIR /var/www/html
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Add user to be used for the resizer.
# this is the first created user -> UID 1000 -> docker host user has access to files created by this user
RUN useradd ResizeUser

# Fehleranzeige im Container
RUN echo "display_errors=On\n" \
         "display_startup_errors=On\n" \
         "error_reporting=E_ALL\n" \
         "log_errors=On\n" \
         "error_log=/proc/self/fd/2" \
         > /usr/local/etc/php/conf.d/dev.ini

# Start Apache im Vordergrund
CMD ["/usr/bin/supervisord", "-c", "supervisord.conf"]

