#!/bin/sh
set -e

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
