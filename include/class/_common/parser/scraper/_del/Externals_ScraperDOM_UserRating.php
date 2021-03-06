<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazn-auto-links/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides methods to extracts each customer review by using DOM objects.
 * 
 * @since       1
 */
class Externals_ScraperDOM_UserRating extends Externals_ScraperDOM_Base {
            
    /**
     * 
     * @return  string
     */
    public function get() {
       
        // Convert image urls for SSL.
        if ( $this->bIsSSL ) {
            $this->setSSLImagesByDOM( $this->oDoc );
        }

        // Modify a tags.
        $this->oDOM->setAttributesByTagName( 
            $this->oDoc, // node
            'a', // tag name
            array( 
                'rel'    => 'nofollow',
                'target' => '_blank',
            ) 
        );        

        // Output
        return $this->oDOM->getInnerHTML( 
            $this->oDoc
        );            
        
    }
    
    
} 