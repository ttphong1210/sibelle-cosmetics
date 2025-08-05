FROM php:7.4-fpm-alpine

# Cài đặt các PHP extensions cần thiết cho Laravel 5.8
RUN apk add --no-cache \
    curl \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    gmp-dev \
    imagemagick \
    imagemagick-dev \
    libwebp-dev \
    git \
    supervisor \
    openssh-client \
    bash \
    nodejs \
    npm \
    yarn

# Cài đặt PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd gmp bcmath opcache pcntl
# Cài đặt Imagick (yêu cầu ImageMagick)
# Ghi chú: Imagick trên PHP 7.4 có thể cần phiên bản ImageMagick cụ thể.
# Nếu gặp lỗi, bạn có thể cần downgrade ImageMagick hoặc tìm phiên bản tương thích.
RUN docker-php-ext-configure imagick --with-imagick=/usr/local && docker-php-ext-install imagick

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc cho Laravel
WORKDIR /var/www/html/backend

# Copy composer.json và composer.lock trước để tận dụng cache Docker
COPY ./backend/composer.json ./backend/composer.lock ./

# Chạy Composer install (chỉ dependency production)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy toàn bộ mã nguồn Laravel vào container
COPY ./backend .

# Thiết lập quyền cho các thư mục Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Cấu hình Supervisor để chạy queue worker (nếu bạn dùng queue)
COPY ./docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port (PHP-FPM chạy trên 9000)
EXPOSE 9000

# Khởi động PHP-FPM và Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
