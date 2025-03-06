<?php
/**
 * Lando-specific configuration
 */
if ('ON' === getenv('LANDO')) {
    // Set host values
    $site_scheme      = 'http';
    $site_host        = 'localhost';
    $strDomainRequest = 'SERVER_NAME';

    if ( isset( $_SERVER['HTTP_HOST'] ) ) {
        $site_host   = $_SERVER['HTTP_HOST'];
        $site_scheme = ! empty( $_SERVER['https'] ) ? 'https' : 'http';
    }

    $objLandoInfo = json_decode(getenv('LANDO_INFO', true));
    define('DB_NAME', $objLandoInfo->database->creds->database);
    define('DB_USER', $objLandoInfo->database->creds->user);
    define('DB_PASSWORD', $objLandoInfo->database->creds->password);
    define('DB_HOST', $objLandoInfo->database->internal_connection->host);
    define('DB_CHARSET', 'utf8');
    define('DB_COLLATE', '');
    define('WP_DEBUG', false);
    define('WP_DEBUG_LOG', false);
    define('WP_DEBUG_SCREEN', false);

    define( 'MULTISITE', false );
	define( 'SUBDOMAIN_INSTALL', false );

    define( 'WP_HOME', $site_scheme . '://' . $site_host );
    define( 'WP_SITEURL', WP_HOME . '/wp' );
    define( 'DISABLE_WP_CRON', true );

    $table_prefix = 'wp_';

    $strContentURL = WP_HOME . '/wp-content';
    define( 'WP_CONTENT_URL', $strContentURL );

    //now we need to set up our salts. this is local so this shouldnt be as crucial to have truly unique keys
    $strHash = getenv('PHP_SHA256');
    $aryKeys = array(
        'AUTH',
        'SECURE_AUTH',
        'LOGGED_IN',
        'NONCE'
    );

    foreach ($aryKeys as $strKey) {
        $strFullKey = $strKey . '_KEY';
        $strFullSalt = $strKey . '_SALT';
        define($strFullKey, $strHash.'-'.$strFullKey);
        define($strFullSalt, $strHash.'-'.$strFullSalt);
    }

    if (MULTISITE) {
        //we need to know which domain has been requested later when COOKIE_DOMAIN is set.
        $strDomainRequest = 'HTTP_HOST';
        if (false !== $strPrimaryDomain = getenv('PRIMARY_DOMAIN')) {
            $aryRoutes = $objLandoInfo->appserver_nginx->urls;
            $strLookForDomain = str_replace('.', '\.', $strPrimaryDomain);
            
            $strPattern = sprintf('/^https:\/\/(%s[^\/]*)/', $strLookForDomain);
            $aryMatched = preg_grep($strPattern, $aryRoutes);
            if (1 === count($aryMatched)) {
                //now we have the _WHOLE_ match, but we need just the domain
                preg_match($strPattern, reset($aryMatched), $aryMatches);
                //@todo this assumes 1 exists without checking first
                $site_host = $aryMatches[1];
            } else {
                //@todo throw an error or just output the message?
                echo '<p>I found zero or too many matches for our primary domain:</p><pre>',var_export($aryMatched, true),'</pre>';exit();
            }
        } else {
            //@todo throw error or output this message?
            echo '<p>PRIMARY DOMAIN has not been defined. Please include this information in your .lando.yaml file.</p>';
        }
    }
} else {
    /**
     * Fill out if you are using a different development environment
     */
}