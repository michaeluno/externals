<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Renews HTTP request caches in the background.
 *  
 * @package     Externals
 * @since       1
 * 
 * @action      add             externals_filter_http_response_cache
 * @action      schedule|add    externals_action_http_cache_renewal
 */
class Externals_Event_HTTPCacheRenewal extends Externals_PluginUtility {
    
    public $sCacheRenewalActionName = 'externals_action_http_cache_renewal';
    
    /**
     * Sets up hooks.
     * @since       1
     */
    public function __construct() {

        // For SimplePie cache renewal events 
        add_filter( 
            'externals_filter_http_response_cache',  // filter hook name
            array( $this, 'replyToModifyCacheRemainedTime' ), // callback
            10, // priority
            4 // number of parameters
        );    
        
        add_action(
            $this->sCacheRenewalActionName, // action hook name
            array( $this, 'replyToRenewCache' ),
            10,
            4
        );

    }
    
    /**
     * 
     * @callback        action      externals_action_http_cache_renewal
     */
    public function replyToRenewCache( $sURL, $iCacheDuration, $aArguments, $sType='wp_remote_get' ) {
            
        $_oHTTP          = new Externals_HTTPClient(
            $sURL, 
            $iCacheDuration, 
            $aArguments
        );
        $_oHTTP->deleteCache();
        $_oHTTP->get();
        
    }
    
    /**
     * 
     * @callback        filter      externals_filter_http_response_cache
     */
    public function replyToModifyCacheRemainedTime( $aCache, $iCacheDuration, $aArguments, $sType='wp_remote_get' ) {
        
        // Check if it is expired.
        if ( 0 >= $aCache[ 'remained_time' ] ) {

            // It is expired. So schedule a task that renews the cache in the background.
            $_bScheduled = $this->_scheduleBackgroundCacheRenewal( 
                $aCache[ 'request_uri' ], 
                $iCacheDuration,
                $aArguments,
                $sType
            );
            
            // Tell the plugin it is not expired. 
            $aCache[ 'remained_time' ] = time();
            
        } 
        
        return $aCache;
                
    }
        /**
         * 
         * @return      boolean
         */
        private function _scheduleBackgroundCacheRenewal( $sURL, $iCacheDuration, $aArguments, $sType ) {
            
            $_sActionName = $this->sCacheRenewalActionName;
            $_aArguments  = array(
                $sURL,
                $iCacheDuration,
                $aArguments,
                $sType
            );
            if ( wp_next_scheduled( $_sActionName, $_aArguments ) ) {
                return false; 
            }
            $_bCancelled = wp_schedule_single_event( 
                time(), // now
                $_sActionName, // the Externals_Event class will check this action hook and executes it with WP Cron.
                $_aArguments // must be enclosed in an array.
            );          
            return false === $_bCancelled
                ? false
                : true;
            
        }

        
}