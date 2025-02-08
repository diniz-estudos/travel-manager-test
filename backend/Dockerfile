FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    libzip-dev \
    cron \
    libpq-dev \
    libicu-dev \
    default-mysql-client

RUN docker-php-ext-install pdo pdo_mysql gd zip intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

COPY start-container.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start-container.sh

WORKDIR /var/www

COPY src/ .

COPY exemplo.env /var/www/.env
RUN chown www-data:www-data /var/www/.env

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install

RUN touch /var/log/cron.log
RUN (crontab -l ; echo "* * * * * cd /var/www && php artisan queue:work --force --once >> /var/www/storage/logs/cron.log 2>&1") | crontab -

RUN chown -R www-data:www-data /var/www/storage

ENTRYPOINT ["/usr/local/bin/start-container.sh"]

EXPOSE 9000
