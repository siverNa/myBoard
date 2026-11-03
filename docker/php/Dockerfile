FROM php:8.3-fpm

RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        zip \
        unzip \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer 설치
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Laravel 프로젝트 복사
COPY . .

# Composer 의존성 설치
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Laravel 캐시 및 업로드 디렉터리 생성과 권한 설정
RUN mkdir -p bootstrap/cache \
    && mkdir -p storage/app/public \
    && chown -R www-data:www-data bootstrap/cache storage
