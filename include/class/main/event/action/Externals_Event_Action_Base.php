<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides base methods for plugin event actions.
 
 * @package      Externals
 * @since       1
 */
abstract class Externals_Event_Action_Base extends Externals_PluginUtility {
    
    /**
     * Sets up hooks.
     * @since       1
     * @param       string      $sActionHookName
     * @param       integer     $iParameters        The number of parameters.
     */
    public function __construct( $sActionHookName, $iParameters=1 ) {

        add_action( 
            $sActionHookName, 
            array( 
                $this, 
                'doAction' 
            ),
            10, // priority
            $iParameters
        );    

    }
    
    /**
     * 
     * @callback        action       
     */
    public function doAction( /* $aArguments */ ) {
        
        $_aParams = func_get_args() + array( null );
        Externals_Debug::log( $_aParams );
        
    }
    
}