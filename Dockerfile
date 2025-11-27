FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install pdo pdo_mysql mbstring gd intl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Jangan composer install di build!
# RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
