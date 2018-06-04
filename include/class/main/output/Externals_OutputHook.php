<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles external outputs.
 * 
 * @package     Externals
 * @since       1       
*/
class Externals_OutputHook extends Externals_PluginUtility {
    
    /**
     * Sets up hooks.
     */
    public function __construct() {
        
        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_external_output',
            array( $this, 'replyToReturnExternalOutput' ),
            10,
            2
        );
        
    }
    
    /**
     * @return      string
     * @since       1
     */
    public function replyToReturnExternalOutput( $sOutput, $aArguments ) {
        return Externals_Output::getInstance( $aArguments )->get();
    }
    
        
}