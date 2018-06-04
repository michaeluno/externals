<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */


/**
 * The bootstrap class that loads external types.
 * 
 * @since        1
 */
class Externals_ExternalTypesLoader {

    public function __construct() {
    
        new Externals_ExternalType_text;
        new Externals_ExternalType_feed;    
        new Externals_ExternalType_wp_readme;
        
    }
    
    
}