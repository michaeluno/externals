<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 */ 
class Externals_SimplePie extends Externals_SimplePie_Base {
        
    /**
     * Initializer.
     */
    public function init() {    
        
        /**
         * We must manually overwrite $feed->sanitize because SimplePie's
         * constructor sets it before we have a chance to set the sanitization class
         */
        $this->set_sanitize_class( 'WP_SimplePie_Sanitize_KSES' );
        $this->sanitize = new WP_SimplePie_Sanitize_KSES();    
        
        /**
         * Store caches in transients.
         */
        $this->set_cache_class( 
            // 'WP_Feed_Cache'
            'Externals_SimplePie_Cache' 
        );
        
        /**
         * Externals uses an own HTTP method with a caching mechanism.
         */
        $this->set_file_class( 
            // 'WP_SimplePie_File' 
            'Externals_SimplePie_File' 
        );       
      
        return parent::init();
        
        $this->set_output_encoding( get_option( 'blog_charset' ) );        
        $this->handle_content_type();
        
        if ( $this->error() ) {
            return new WP_Error( 'simplepie-error', $this->error() );        
        }
        
    }

    
}