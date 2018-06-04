<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Echoes or returns the output of externals.
 * @since       1
 * @return      string
 */
function getExternals( $aArguments, $bEcho=true ) {
    
    $_sOutput = apply_filters(  
        Externals_Registry::HOOK_SLUG . '_filter_external_output',
        '', // output
        $aArguments
    );
    
    if ( $bEcho ) {
        echo $_sOutput;
        return;
    }
    
    return $_sOutput;
        
}