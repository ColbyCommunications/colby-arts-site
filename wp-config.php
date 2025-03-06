<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('PLATFORM_VARIABLES', json_decode($_ENV['PLATFORM_VARS'], true));
define( 'ALGOLIA_INDEX_NAME_PREFIX', 'kinsta_colbyarts_' );
define('WP_CONTENT_DIR', dirname(__FILE__) . '/web/wp-content');
if ( 'ON' === getenv( 'LANDO' ) ) {
    if ( file_exists( dirname( __FILE__ ) . '/wp-config-local.php' ) ) {
		include dirname( __FILE__ ) . '/wp-config-local.php';
	}
} else {
    // NOTICE: Don't forget to comment out the wp-settings require_once in wp-config-kinsta.php
    include_once dirname( __FILE__ ) . '/wp-config-kinsta.php';
}
