FROM php:8.5.12-apache

# Installiere GD und Cron
# 8.5.12 fixes CVE-2026-109618 and CVE-2026-109619 (unauthenticated RCE).
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    cron \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd \
    && rm -rf /var/lib/apt/lists/*

# Apache Rewrite aktivieren
RUN a2enmod rewrite

# Apache root setzen & Rechte
WORKDIR /var/www/html
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Fehleranzeige im Container
RUN echo "display_errors=On\n" \
         "display_startup_errors=On\n" \
         "error_reporting=E_ALL\n" \
         "log_errors=On\n" \
         "error_log=/proc/self/fd/2" \
         > /usr/local/etc/php/conf.d/dev.ini

# Start Apache im Vordergrund
CMD ["apache2-foreground"]