#!/bin/bash
set -e

echo "Starting WordPress application..."

# Check if wp-config.php exists, if not create from sample
if [ ! -f "wp-config.php" ]; then
  echo "Creating wp-config.php..."
  cp wp-config-sample.php wp-config.php
  
  # Update database credentials from environment variables
  sed -i "s/database_name_here/${DB_NAME:-wordpress}/g" wp-config.php
  sed -i "s/username_here/${DB_USER:-wordpress_user}/g" wp-config.php
  sed -i "s/password_here/${DB_PASSWORD}/g" wp-config.php
  sed -i "s/localhost/${DB_HOST}/g" wp-config.php
fi

# Create uploads directory if it doesn't exist
mkdir -p wp-content/uploads
chmod 755 wp-content/uploads

# Start PHP built-in server or Apache
exec php -S 0.0.0.0:3000