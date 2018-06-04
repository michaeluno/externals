<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates caches for the unit.
 * 
 * @package      Externals
 * @since        1
 * @action       externals_action_prefetch
 */
class Externals_Event_Action_Prefetch extends Externals_Event_Action_Base {
        
    /**
     * 
     * @callback        action        externals_action_prefetch
     */
    public function doAction( /* $iExternalID */ ) {
        
        $_aParams    = func_get_args() + array( 0 );
        $iExternalID = $_aParams[ 0 ];

        $_sExternalType = get_post_meta(
            $iExternalID,
            '_type',
            true
        );
        if ( ! $_sExternalType ) {
            return;
        }

        // Just call the output.
        $_sExternalOptionClassName = "Externals_ExternalArgument_" . $_sExternalType;
        $_oArguments               = new $_sExternalOptionClassName( $iExternalID );
        $_aExternalOptions         = $_oArguments->get();
        Externals_Output::getInstance( $_aExternalOptions )->get();
        
    }   
    
}