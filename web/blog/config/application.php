<?php
$root_dir = dirname(dirname(__FILE__));

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
Dotenv::load($root_dir);
Dotenv::required(array('DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL'));

/**
 * Set up our global environment constant and load its config first
 * Default: development
 */
define('WP_ENV', getenv('WP_ENV') ? getenv('WP_ENV') : 'development');

$env_config = dirname(__FILE__) . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
  require_once $env_config;
}

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $root_dir . CONTENT_DIR);
define('WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . "/blog" . CONTENT_DIR);

/**
 * DB settings
 */
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = 'blog_';

/**
 * Authentication Unique Keys and Salts
 * https://api.wordpress.org/secret-key/1.1/salt
 */
$salt = getenv("SALT");
define('AUTH_KEY',         $salt);
define('SECURE_AUTH_KEY',  $salt);
define('LOGGED_IN_KEY',    $salt);
define('NONCE_KEY',        $salt);
define('AUTH_SALT',        $salt);
define('SECURE_AUTH_SALT', $salt);
define('LOGGED_IN_SALT',   $salt);
define('NONCE_SALT',       $salt);

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', true);
define('DISALLOW_FILE_EDIT', true);

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
  define('ABSPATH', $root_dir . '/wp/');
}
