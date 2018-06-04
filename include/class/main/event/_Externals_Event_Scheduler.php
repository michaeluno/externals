<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles plugin event scheduling.
 * 
 * @since       1
 * @action      schedule        externals_action_api_get_product_info
 */
class Externals_Event_Scheduler {
    
    /**
     * Schedules a pre-fetch task.
     * @since       1
     * @return      void
     * @action      externals_action_prefetch
     */
    static public function prefetch( $iPostID ) {
        
        if ( ! $iPostID ) {
            return;
        }
        self::_scheduleTask( 
            'externals_action_prefetch',  // action name
            $iPostID // arguments
        );        
        
    }    
    
    /**
     * 
     * @action      schedule        externals_action_api_get_customer_review
     */
    static public function getCustomerReviews( $sURL, $sAISN, $sLocale, $iCacheDuration ) {

        $_bScheduled = self::_scheduleTask( 
            'externals_action_api_get_customer_review',  // action name
            array( $sURL, $sAISN, $sLocale, $iCacheDuration )
        );
        if ( ! $_bScheduled ) {
            return $_bScheduled;
        }
        
        // Loads the site in the background. The method takes care of doing it only once in the entire page load.
        Externals_Shadow::see();
        return true;        
    
    }

    /**
     * Schedules an action of getting product information in the background.
     * 
     * @since       1
     * @action      schedule        externals_action_api_get_product_info
     */
    static public function getProductInfo( $sAssociateID, $sASIN, $sLocale, $iCacheDuration ) {
    
        $_oOption   = Externals_Option::getInstance();
        if ( ! $_oOption->isAPIConnected() ) {
            return false;
        }
        
        $_bScheduled = self::_scheduleTask( 
            'externals_action_api_get_product_info',  // action name
            array( $sAssociateID, $sASIN, $sLocale, $iCacheDuration )
        );
        if ( ! $_bScheduled ) {
            return $_bScheduled;
        }
        
        // Loads the site in the background. The method takes care of doing it only once in the entire page load.
        Externals_Shadow::see();
        return true;
        
    }
    
    /**
     * Stores how many actions are schedules bu action name.
     */
    static protected $_aCounters = array();

    /**
     * @return      boolean
     */
    static private function _scheduleTask( /* $_sActionName, $aArgument1, $aArgument2, ... */ ) {
         
        $_aParams       = func_get_args() + array( null, array() );
        $_sActionName   = array_shift( $_aParams ); // the first element
        
        // $_aArguments    = array( $_aParams[ 1 ] );
        $_aArguments    = $_aParams; // the rest 

        // If already scheduled, skip.
        if ( wp_next_scheduled( $_sActionName, $_aArguments ) ) {
            return false; 
        }
        
        self::$_aCounters[ $_sActionName ] = isset( self::$_aCounters[ $_sActionName ] )
            ? self::$_aCounters[ $_sActionName ] + 1
            : 1;
        
        wp_schedule_single_event( 
            time() + self::$_aCounters[ $_sActionName ] - 1, // now + registering counts, giving one second delay each to avoid timeouts when handling tasks and api rate limit runout.
            $_sActionName, // the Externals_Event class will check this action hook and executes it with WP Cron.
            $_aArguments // must be enclosed in an array.
        );            

        return true;
        
    }    
    
}