FROM php:8.2-apache

# 1. Cài đặt các thư viện cần thiết và extension PostgreSQL cho PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# 2. Bật mod_rewrite của Apache (cần thiết cho hệ thống routing của Laravel)
RUN a2enmod rewrite

# 3. Trỏ thư mục web gốc vào thư mục /public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copy toàn bộ code từ GitHub vào trong server
WORKDIR /var/www/html
COPY . .

# 5. Cài đặt Composer và các thư viện của Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. Cấp quyền cho phép Laravel ghi file cache và log
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Mở port 80 để web hoạt động
EXPOSE 80