FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client \
    gnupg

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure GD with JPEG and FreeType support
RUN docker-php-ext-configure gd --with-jpeg --with-freetype

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/custom-php.ini

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build
RUN npm install && npm run build

# Expose port
EXPOSE 8000

# Start command
CMD php artisan serve --host=0.0.0.0 --port=8000
