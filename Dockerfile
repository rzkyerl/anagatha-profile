FROM php:8.2

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
# Ensure we exit on critical errors, but continue on warnings
set -u  # Exit on undefined variables

echo "=========================================="
echo "LARAVEL STARTUP SCRIPT STARTED"
echo "Timestamp: $(date)"
echo "Working Directory: $(pwd)"
echo "User: $(whoami)"
echo "PORT: ${PORT:-8000}"
echo "=========================================="

# Log critical env that must exist
if [ -z "${APP_KEY:-}" ]; then
    echo "ERROR: APP_KEY is not set in the environment."
    exit 1
fi

# Clear any cached config FIRST
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true

# Regenerate autoload to ensure all classes are available
composer dump-autoload --optimize --no-interaction 2>&1 || echo "Warning: composer dump-autoload failed, continuing..."

# Run migrations (safe to skip if DB not available)
php artisan migrate --force 2>&1 || echo "Warning: migrate failed, continuing..."

echo "Caching Laravel configuration..."
php artisan config:cache 2>&1 || {
    echo "WARNING: config:cache failed, clearing and continuing without cache..."
    php artisan config:clear 2>&1 || true
}
php artisan route:cache 2>&1 || {
    echo "WARNING: route:cache failed, clearing route cache..."
    php artisan route:clear 2>&1 || true
}
php artisan view:cache 2>&1 || echo "Warning: view:cache failed, continuing..."

# Ensure storage is writable
chmod -R 775 storage bootstrap/cache 2>&1 || true
chown -R www-data:www-data storage bootstrap/cache 2>&1 || true

# Create required storage directories
mkdir -p storage/app/company 2>&1 || true
chmod -R 775 storage/app/company 2>&1 || true
chown -R www-data:www-data storage/app/company 2>&1 || true

# Start PHP built-in server
PORT=${PORT:-8000}
echo "=========================================="
echo "Starting Laravel application server"
echo "PORT: $PORT"
echo "=========================================="

# Verify the server can start (test command)
echo "Verifying PHP and Laravel installation..."
php artisan --version || {
    echo "ERROR: php artisan command failed!"
    exit 1
}

# Test if we can access the health endpoint (basic check)
echo "Testing application bootstrap..."
php artisan route:list --path=health 2>&1 | head -5 || echo "Route list check completed"

# Start PHP built-in server (use exec to replace shell process)
# This ensures the process runs as PID 1 and receives signals properly
echo "Starting PHP built-in server on 0.0.0.0:$PORT..."
echo "Server will be available at http://0.0.0.0:$PORT"
echo "Health check endpoint: http://0.0.0.0:$PORT/health"
echo "=========================================="

# Use exec to replace shell process and ensure proper signal handling
echo "About to start PHP server..."
echo "Command: php artisan serve --host=0.0.0.0 --port=$PORT"
echo "=========================================="

# Start the server - this will replace the current process
exec php artisan serve --host=0.0.0.0 --port=$PORT 2>&1
EOF
RUN chmod +x /start.sh

# Start PHP built-in server (Railway sets PORT automatically)
# Use bash explicitly to ensure script runs and output is visible
CMD ["/bin/bash", "/start.sh"]

