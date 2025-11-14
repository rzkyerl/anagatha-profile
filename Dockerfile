FROM php:8.2-fpm

# Install system dependencies dan Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies dan build assets
RUN if [ -f "package-lock.json" ]; then npm ci; else npm install; fi && \
    npm run build && \
    rm -rf node_modules

# Don't cache config during build (needs .env at runtime)
# Config will be cached in start script

# Expose port (Railway will set PORT env variable at runtime)
EXPOSE 8000

# Create startup script
RUN cat > /start.sh << 'EOF'
#!/bin/bash

echo "Starting Laravel application..."
echo "PORT: ${PORT:-8000}"

# Wait for .env to be available
if [ ! -f .env ]; then
    echo "Warning: .env file not found. Creating from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "Created .env from .env.example"
    else
        echo "Error: .env.example not found!"
        exit 1
    fi
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force 2>&1 || echo "Warning: key:generate failed, continuing..."
fi

# Clear any cached config first
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true

# Cache Laravel config (only if .env exists and valid)
if [ -f .env ]; then
    echo "Caching Laravel configuration..."
    php artisan config:cache 2>&1 || echo "Warning: config:cache failed, continuing..."
    php artisan route:cache 2>&1 || echo "Warning: route:cache failed, continuing..."
    php artisan view:cache 2>&1 || echo "Warning: view:cache failed, continuing..."
fi

# Ensure storage is writable
chmod -R 775 storage bootstrap/cache 2>&1 || true
chown -R www-data:www-data storage bootstrap/cache 2>&1 || true

echo "Starting PHP built-in server on port ${PORT:-8000}..."
echo "Server will be available at http://0.0.0.0:${PORT:-8000}"

# Start PHP built-in server (use exec to replace shell process)
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} 2>&1
EOF
RUN chmod +x /start.sh

# Start PHP built-in server (Railway sets PORT automatically)
CMD ["/start.sh"]

