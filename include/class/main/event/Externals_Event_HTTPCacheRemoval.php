<?php
/**
 * Fetch Tweets
 *
 * http://en.michaeluno.jp/amazon-auto-links/
 * Copyright (c) 2013-2016 Michael Uno
 *
 */

/**
 * Removes expired HTTP request caches.
 *
 * @since       2.5.0
 * @action      schedule|add    fetch_tweets_action_http_cache_removal
 */
class Externals_Event_HTTPCacheRemoval extends Externals_PluginUtility {

    private $___sActionName = 'externals_action_http_cache_removal';

    public function __construct() {

        $_oOption   = Externals_Option::getInstance();
        $_iInterval = $_oOption->get( array( 'cache', 'clear_interval', 'size' ), 7 )
            * $_oOption->get( array( 'cache', 'clear_interval', 'unit' ), 86400 );
        $this->scheduleWPCronActionOnce(
            $this->___sActionName,
            array(),
            $_iInterval
        );

        add_action( $this->___sActionName, array( $this, 'replyToDoAction' ) );

    }

    /**
     * Deletes expired caches.
     */
    public function replyToDoAction( /* */ ) {
        $_oHTTPRequestTable = new Externals_DatabaseTable_externals_request_cache;
        $_oHTTPRequestTable->deleteExpired();
    }

}
