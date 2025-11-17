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
# Function to safely inject env var (handles values with spaces)
inject_env_var() {
    local var_name=$1
    local var_value=$2
    if [ -n "$var_value" ]; then
        # Quote value if it contains spaces or special characters
        local formatted_value="$var_value"
        if echo "$var_value" | grep -qE '[[:space:]"#]'; then
            # Escape quotes in value and wrap in quotes
            formatted_value=$(echo "$var_value" | sed 's/"/\\"/g')
            formatted_value="\"${formatted_value}\""
        fi
        # Remove existing line and add new one (safer than sed replacement)
        if grep -q "^${var_name}=" .env 2>/dev/null; then
            grep -v "^${var_name}=" .env > .env.tmp 2>/dev/null && mv .env.tmp .env || true
        fi
        echo "${var_name}=${formatted_value}" >> .env
        echo "  - ${var_name} injected"
    else
        echo "  - ${var_name} not found in environment"
    fi
}

echo "Injecting environment variables into .env file..."
inject_env_var "APP_KEY" "$APP_KEY"
inject_env_var "GOOGLE_SHEET_ID" "$GOOGLE_SHEET_ID"
inject_env_var "GOOGLE_SHEET_NAME" "$GOOGLE_SHEET_NAME"

# Inject GOOGLE_CREDENTIALS_JSON (JSON string needs special handling)
if [ -n "$GOOGLE_CREDENTIALS_JSON" ]; then
    # Remove existing line
    if grep -q "^GOOGLE_CREDENTIALS_JSON=" .env 2>/dev/null; then
        grep -v "^GOOGLE_CREDENTIALS_JSON=" .env > .env.tmp 2>/dev/null && mv .env.tmp .env || true
    fi
    # JSON strings need to be properly escaped and quoted
    # Escape backslashes first, then quotes, then wrap in quotes
    escaped_json=$(echo "$GOOGLE_CREDENTIALS_JSON" | sed 's/\\/\\\\/g' | sed 's/"/\\"/g')
    echo "GOOGLE_CREDENTIALS_JSON=\"${escaped_json}\"" >> .env
    echo "  - GOOGLE_CREDENTIALS_JSON injected"
else
    echo "  - GOOGLE_CREDENTIALS_JSON not found in environment"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force 2>&1 || echo "Warning: key:generate failed, continuing..."
fi

# Clear any cached config FIRST (before injecting env vars)
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true

# Regenerate autoload to ensure all classes are available
composer dump-autoload --optimize --no-interaction 2>&1 || echo "Warning: composer dump-autoload failed, continuing..."

# Run migrations (after env vars are injected)
php artisan migrate --force 2>&1 || echo "Warning: migrate failed, continuing..."

# IMPORTANT: Cache config AFTER environment variables are injected
# This ensures Laravel reads from environment variables, not cached .env values
if [ -f .env ]; then
    echo "Caching Laravel configuration (after env vars injected)..."
    php artisan config:cache 2>&1 || {
        echo "WARNING: config:cache failed, clearing and continuing without cache..."
        php artisan config:clear 2>&1 || true
    }
    php artisan route:cache 2>&1 || {
        echo "WARNING: route:cache failed, clearing route cache..."
        php artisan route:clear 2>&1 || true
    }
    php artisan view:cache 2>&1 || echo "Warning: view:cache failed, continuing..."
fi

# Ensure storage is writable
chmod -R 775 storage bootstrap/cache 2>&1 || true
chown -R www-data:www-data storage bootstrap/cache 2>&1 || true

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

