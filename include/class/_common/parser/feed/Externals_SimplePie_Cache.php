<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 *
 */
class Externals_SimplePie_Cache extends SimplePie_Cache {
    
    /**
     * Create a new SimplePie_Cache object
     *
     * @static
     * @access public
     */
    function create( $location, $sFileName, $extension ) {        
        return new Externals_SimplePie_Cache_Transient(
            $location, 
            $filename, 
            $extension
        );
    } 
    
}