#!/bin/bash

# Exit on fail
set -e

# If .env does not exist, create it from .env.example
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# We can rely on Render.com Environment Variables to populate the .env or override them.
# However, Laravel 5.4 dot env loader might need the keys to exist in the .env file to be readable if not using getenv() everywhere.
# But usually env() helper reads from $_ENV which is populated by system vars if they exist.

# Generate key if not set (though it should be set in Render env vars)
if [ -z "$APP_KEY" ]; then
    echo "Generating Application Key..."
    php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache config (Important for production speed and env var loading)
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
