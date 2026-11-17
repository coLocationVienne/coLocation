#!/bin/sh
set -e

# Ensure git considers the working directory safe (fixes dubious ownership in mounted/container environments)
git config --global --add safe.directory /var/www/html || true

# Install composer dependencies if composer.json exists and vendor is missing
if [ -f "composer.json" ]; then
    if [ ! -d "vendor" ]; then
        echo "Vendor directory not found. Installing PHP dependencies..."
        composer install --no-interaction --no-progress --optimize-autoloader
    else
        echo "Vendor directory exists. Skipping automatic install."
    fi
fi

# Execute the main command (passed as arguments)
exec "$@"
