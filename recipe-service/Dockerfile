FROM php:8.2-apache-bookworm

# 1. Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    git \
    unzip \
    zip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Установка расширений PHP
RUN docker-php-ext-configure zip && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    zip

# 3. Настройка Apache
RUN a2enmod rewrite
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# 4. Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Рабочая директория
WORKDIR /var/www/html

# 6. Копирование файлов проекта
COPY . .

# 7. Установка зависимостей Composer (с обработкой ошибок)
RUN if [ -f "composer.lock" ]; then \
    composer install --no-dev --no-scripts --no-interaction || \
    { rm -f composer.lock && composer install --no-dev --no-scripts --no-interaction; }; \
    else \
    composer install --no-dev --no-scripts --no-interaction; \
    fi && \
    composer dump-autoload --optimize

RUN mkdir -p /var/www/html/storage/logs && \
    chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage && \
    composer require guzzlehttp/guzzle

EXPOSE 80
CMD ["apache2-foreground"]