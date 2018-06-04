<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazn-auto-links/
 * Copyright (c) 2013-2015 Michael Uno
 * 
 */

/**
 * Handles 'tag' unit options.
 * 
 * @since       3
 */
class Externals_ExternalArgument_tag extends Externals_ExternalArgument_Base {
    
    /**
     * Stores the unit type.
     * @remark      Should be overridden in an extended class.
     */
    public $sExternalType = 'tag';    
    
    /**
     * Stores the default structure and key-values of the unit.
     * @remark      Accessed from the base class constructor to construct a default option array.
     */    
    static public $aStructure_Default = array(

        'tags'          => '',
        'customer_id'   => '',
        'feed_type'     => array(
            'new' => true,
        ),
        'threshold'     => 2,
        'sort'          => 'random',    // date, title, title_descending    
       
    );
    
    
    /**
     * 
     * @since       3 
     */
    protected function format( array $aExternalOptions ) {

        $aExternalOptions = parent::format( $aExternalOptions );
        
        // If nothing is checked for the feed type, enable the 'new' item.
        $_aCheckedFeedTypes = array_filter( $aExternalOptions[ 'feed_type' ] );
        if ( empty( $_aCheckedFeedTypes ) ) {
            $aExternalOptions[ 'feed_type' ][ 'new' ] = true;
        }  
        
        
        return $aExternalOptions;
        
    }    
    

    

}