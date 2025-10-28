#!/bin/bash
set -e

echo "Building WordPress application..."

# Install PHP dependencies if composer.json exists
if [ -f "composer.json" ]; then
  echo "Installing PHP dependencies..."
  composer install --no-dev --optimize-autoloader
fi

echo "Build completed successfully!"