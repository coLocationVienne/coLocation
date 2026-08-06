#!/bin/sh
set -e

# Install composer dependencies if composer.json exists
if [ -f "composer.json" ]; then
    echo "Installing/Updating PHP dependencies..."
    composer install --no-interaction --no-progress --optimize-autoloader
fi

# Execute the main command (passed as arguments)
exec "$@"
