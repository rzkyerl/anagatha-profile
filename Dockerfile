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
echo "SERVICE_TYPE: ${SERVICE_TYPE:-web}"
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

# Inject environment variables into .env file if they exist
# This ensures Railway environment variables are used
if [ -n "$APP_KEY" ]; then
    sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env || echo "APP_KEY=$APP_KEY" >> .env
fi
if [ -n "$DB_CONNECTION" ]; then
    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=$DB_CONNECTION|" .env || echo "DB_CONNECTION=$DB_CONNECTION" >> .env
fi
if [ -n "$DB_HOST" ]; then
    sed -i "s|^DB_HOST=.*|DB_HOST=$DB_HOST|" .env || echo "DB_HOST=$DB_HOST" >> .env
fi
if [ -n "$DB_PORT" ]; then
    sed -i "s|^DB_PORT=.*|DB_PORT=$DB_PORT|" .env || echo "DB_PORT=$DB_PORT" >> .env
fi
if [ -n "$DB_DATABASE" ]; then
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|" .env || echo "DB_DATABASE=$DB_DATABASE" >> .env
fi
if [ -n "$DB_USERNAME" ]; then
    sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" .env || echo "DB_USERNAME=$DB_USERNAME" >> .env
fi
if [ -n "$DB_PASSWORD" ]; then
    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env || echo "DB_PASSWORD=$DB_PASSWORD" >> .env
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force 2>&1 || echo "Warning: key:generate failed, continuing..."
fi

# Run migrations (for queue table)
php artisan migrate --force 2>&1 || echo "Warning: migrate failed, continuing..."

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

# Check if this is a queue worker service
if [ "${SERVICE_TYPE}" = "worker" ]; then
    echo "Starting queue worker..."
    echo "Queue connection: ${QUEUE_CONNECTION:-database}"
    exec php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=60
else
    echo "Starting PHP built-in server on port ${PORT:-8000}..."
    echo "Server will be available at http://0.0.0.0:${PORT:-8000}"
    # Start PHP built-in server (use exec to replace shell process)
    exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} 2>&1
fi
EOF
RUN chmod +x /start.sh

# Start PHP built-in server (Railway sets PORT automatically)
CMD ["/start.sh"]

