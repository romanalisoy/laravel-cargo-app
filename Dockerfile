FROM php:8.3-fpm
LABEL authors="Eldar Muzaffarov"

RUN apt update -y && apt install -y libzip-dev zip unzip libpq-dev libcurl4-gnutls-dev nginx git libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    openssl libssl-dev dos2unix libmagickwand-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --enable-gd
RUN docker-php-ext-configure exif
RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-configure zip

RUN docker-php-ext-install gd exif pcntl zip pdo pdo_mysql bcmath curl opcache
RUN pecl install redis mongodb imagick
RUN docker-php-ext-enable redis mongodb exif imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
