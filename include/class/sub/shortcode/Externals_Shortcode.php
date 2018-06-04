<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles plugin's shortcodes.
 * 
 * @package     Externals
 * @since       1
 */
class Externals_Shortcode extends Externals_PluginUtility {

    /**
     * Registers the shortcode(s).
     */
    public function __construct( $asShortCode ) {
        foreach( $this->getAsArray( $asShortCode ) as $_sShortCode ) {
            add_shortcode( 
                $_sShortCode, 
                array( $this, '_replyToGetOutput' ) 
            );
        }
    }    
        /**
         * Returns the output based on the shortcode arguments.
         * 
         * @since       1
         */
        public function _replyToGetOutput( $aArguments ) {
            return apply_filters(  
                Externals_Registry::HOOK_SLUG . '_filter_external_output',
                '',
                $aArguments
            );
        }    

}