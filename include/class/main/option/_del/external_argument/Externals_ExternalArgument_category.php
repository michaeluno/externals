<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazn-auto-links/
 * Copyright (c) 2013-2015 Michael Uno
 * 
 */

/**
 * Handles category unit options.
 * 
 * @since       3

 */
class Externals_ExternalArgument_category extends Externals_ExternalArgument_Base {

    /**
     * Stores the default structure and key-values of the unit.
     * @remark      Accessed from the base class constructor to construct a default option array.
     */
    static public $aStructure_Default = array(

        'sort'                  => 'random', // date, title, title_descending    
        'keep_raw_title'        => false,    // this is for special sorting method.
        'feed_type'             => array(
            'bestsellers'           => true, 
            'new-releases'          => false,
            'movers-and-shakers'    => false,
            'top-rated'             => false,
            'most-wished-for'       => false,
            'most-gifted'           => false,    
        ),

        'categories'            => array(),    
        'categories_exclude'    => array(),
        
        // The below are retrieved separately and the default values will be assigned in a different process
        // So do not set these here.
        // 'item_format' => null,   // (string)
        // 'image_format' => null,  // (string)
        // 'title_format' => null,  // (string)
         

    );


    /**
     * 
     * @since       3 
     */
    protected function format( array $aExternalOptions ) {

        $aExternalOptions = parent::format( $aExternalOptions );
        
        // If nothing is checked for the feed type, enable the bestseller item.
        $_aCheckedFeedTypes = array_filter( $aExternalOptions[ 'feed_type' ] );
        if ( empty( $_aCheckedFeedTypes ) ) {
            $aExternalOptions[ 'feed_type' ][ 'bestsellers' ] = true;
        }  
        
        return $aExternalOptions;
        
    }    
    

    

}