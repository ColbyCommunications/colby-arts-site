<?php
if ( 'master' == getenv( 'PLATFORM_BRANCH' ) ) {
    define( 'ALGOLIA_INDEX_NAME_PREFIX', 'prod_colbyarts_' );
} else {
    define( 'ALGOLIA_INDEX_NAME_PREFIX', 'platform_colbyarts_' );
}