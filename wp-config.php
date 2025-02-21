<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('PLATFORM_VARIABLES', json_decode($_ENV['PLATFORM_VARS'], true));
define( 'ALGOLIA_INDEX_NAME_PREFIX', 'kinsta_colbyarts_' );
define('WP_CONTENT_DIR', dirname(__FILE__) . '/web/wp-content');

include dirname( __FILE__ ) . '/wp-config-kinsta.php';
