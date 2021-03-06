<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazn-auto-links/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides abstract members for scraping web contents using PHP Simple HTML DOM Parser.
 * 
 * @since       1
 */
abstract class Externals_ScraperPSHDP_Base extends Externals_PluginUtility {
    
    /**
     * Stores a simple html parser DOM object.
     */
    public $oSimpleDOM;
    
    /**
     * Sets up properties.
     */
    public function __construct( $sURLOrFIlePathOrHTML ) {

        if ( ! class_exists( 'simple_html_dom_node' ) ) {
            include( Externals_Registry::$sDirPath . '/include/library/simple_html_dom.php' );
        }     
        
        // If a given value is a url,
        if ( filter_var( $sURLOrFIlePathOrHTML, FILTER_VALIDATE_URL ) ){ 
            $this->oSimpleDOM = file_get_html( 
                $sURLOrFIlePathOrHTML
            );                    
        }        
        // Else if it is a file path,
        else if ( file_exists( $sURLOrFIlePathOrHTML ) ) {
            $this->oSimpleDOM = str_get_html( 
                file_get_contents( $sURLOrFIlePathOrHTML )
            );            
        } 
        // Else, treat it as HTML contents.
        else {
            $this->oSimpleDOM = str_get_html( 
                $sURLOrFIlePathOrHTML
            );
        }
        
    }
    
} 