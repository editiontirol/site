<?php

  if(!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__).'/');
  }

  define('DB_CHARSET', 'utf8');
  define('DB_COLLATE', '');
  if(!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
  }
  $table_prefix = 'et_';

  define('UPLOADS', 'uploads');

  define('WP_CONFIG_DEV',        ABSPATH.'../wp-config-dev.php');
  define('WP_CONFIG_PRODUCTION', ABSPATH.'../wp-config-production.php');

  define('WP_MEMORY_LIMIT', '1024M');

  if(file_exists(WP_CONFIG_DEV)) {
    require_once(WP_CONFIG_DEV);
    define('WP_DEBUG', true);
  } else {
    require_once(WP_CONFIG_PRODUCTION);
    define('DISALLOW_FILE_EDIT', true);
    define('WP_DEBUG', false);
  }

  require_once(ABSPATH . 'wp-settings.php');
