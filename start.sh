#!/bin/bash
set -e

echo "Starting WordPress application..."
echo "DB_HOST: ${DB_HOST}"
echo "DB_NAME: ${DB_NAME}"
echo "DB_USER: ${DB_USER}"

# Wait for MySQL to be ready
if [ ! -z "$DB_HOST" ]; then
  echo "Waiting for MySQL to become available at $DB_HOST:3306..."
  max_attempts=60
  attempt=0
  while [ $attempt -lt $max_attempts ]; do
    if nc -z "$DB_HOST" 3306 2>/dev/null; then
      echo "✓ MySQL is ready!"
      break
    fi
    attempt=$((attempt + 1))
    echo "  → Attempt $attempt/$max_attempts..."
    sleep 1
  done
  if [ $attempt -eq $max_attempts ]; then
    echo "⚠ Warning: MySQL didn't respond in time, proceeding anyway..."
  fi
fi

# Check if wp-config.php exists, if not create it
if [ ! -f "wp-config.php" ]; then
  echo "Creating wp-config.php..."
  echo "  Database Config: ${DB_HOST} / ${DB_NAME} / ${DB_USER}"
  
  # Validate database credentials
  if [ -z "$DB_HOST" ] || [ -z "$DB_NAME" ] || [ -z "$DB_USER" ]; then
    echo "⚠ Warning: Database credentials incomplete!"
    echo "    DB_HOST: ${DB_HOST:-EMPTY}"
    echo "    DB_NAME: ${DB_NAME:-EMPTY}"
    echo "    DB_USER: ${DB_USER:-EMPTY}"
    echo "    DB_PASSWORD: ${DB_PASSWORD:+SET}${DB_PASSWORD:-EMPTY}"
  fi
  
  # Generate unique salts for security
  AUTH_KEY=$(openssl rand -base64 32)
  SECURE_AUTH_KEY=$(openssl rand -base64 32)
  LOGGED_IN_KEY=$(openssl rand -base64 32)
  NONCE_KEY=$(openssl rand -base64 32)
  AUTH_SALT=$(openssl rand -base64 32)
  SECURE_AUTH_SALT=$(openssl rand -base64 32)
  LOGGED_IN_SALT=$(openssl rand -base64 32)
  NONCE_SALT=$(openssl rand -base64 32)
  
  cat > wp-config.php << EOF
<?php
/**
 * The base configuration for WordPress
 */

// ** Database settings ** //
define('DB_NAME', '${DB_NAME:-wordpress}');
define('DB_USER', '${DB_USER:-wordpress_user}');
define('DB_PASSWORD', '${DB_PASSWORD:-}');
define('DB_HOST', '${DB_HOST:-localhost}');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// ** Authentication Unique Keys and Salts ** //
define('AUTH_KEY',         '$AUTH_KEY');
define('SECURE_AUTH_KEY',  '$SECURE_AUTH_KEY');
define('LOGGED_IN_KEY',    '$LOGGED_IN_KEY');
define('NONCE_KEY',        '$NONCE_KEY');
define('AUTH_SALT',        '$AUTH_SALT');
define('SECURE_AUTH_SALT', '$SECURE_AUTH_SALT');
define('LOGGED_IN_SALT',   '$LOGGED_IN_SALT');
define('NONCE_SALT',       '$NONCE_SALT');

// ** WordPress Database Table prefix ** //
\$table_prefix = 'wp_';

// ** For developers: WordPress debugging mode ** //
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Enable REST API for headless WordPress
define('WP_REST_ENABLED', true);

/* Add any custom defines before this line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined('ABSPATH') ) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
EOF
  echo "wp-config.php created successfully"
fi

# Create uploads directory if it doesn't exist
mkdir -p wp-content/uploads
chmod 755 wp-content/uploads

# Configure Apache to listen on Railway's PORT (default 80 for Docker)
# Railway will map this to the public port
apache2-foreground