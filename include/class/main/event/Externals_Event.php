<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Plugin event handler.
 * 
 * @package      Externals
 * @since        1
 */
class Externals_Event {

    /**
     * Triggers event actions.
     */
    public function __construct() {

        new Externals_Event_HTTPCacheRenewal;
        new Externals_Event_HTTPCacheRemoval;

        new Externals_Event_Action_Prefetch(
            'externals_action_prefetch'
        );
        
        
        // This must be called after the above action hooks are added.
        $_oOption               = Externals_Option::getInstance();
        if ( 'intense' === $_oOption->get( 'cache', 'chaching_mode' ) ) {
            // Force executing actions.
            new Externals_Shadow(    
                array(
                    'externals_action_prefetch',
                    'externals_action_http_cache_renewal',
                )
            );               
        } else {
            if ( Externals_Shadow::isBackground() ) {
                exit;
            }
        }

    }
    
}