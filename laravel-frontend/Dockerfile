FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Install dependencies
RUN composer install
RUN npm install

# Permission
RUN chmod -R 777 storage bootstrap/cache

# Expose ports
EXPOSE 8000
EXPOSE 5173

# Run Laravel + Vite
CMD sh -c "php artisan serve --host=0.0.0.0 --port=8000 & npm run dev -- --host 0.0.0.0"