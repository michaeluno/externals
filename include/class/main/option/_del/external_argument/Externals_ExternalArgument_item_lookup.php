<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazn-auto-links/
 * Copyright (c) 2013-2015 Michael Uno
 * 
 */

/**
 * Handles ItemLookUp unit options.
 * 
 * @since       3

 */
class Externals_ExternalArgument_item_lookup extends Externals_ExternalArgument_search {

    /**
     * Stores the default structure and key-values of the unit.
     * @remark      Accessed from the base class constructor to construct a default option array.
     */
    public static $aStructure_Default = array(
        
        // Main fields
        'ItemId'        => null,
        'IdType'        => 'ASIN',
        'Operation'     => 'ItemLookup',
        'SearchIndex'   => 'All',

        // Advanced fields
        'MerchantId'    => 'All',
        'Condition'     => 'New',
        
    );

    /**
     * 
     * @return  array
     */
    protected function getDefaultOptionStructure() {
        return self::$aStructure_Default + parent::$aStructure_Default;
    }

    /**
     * 
     * @since       3 
     */
    protected function format( array $aExternalOptions ) {

        $aExternalOptions = parent::format( $aExternalOptions )
            + Externals_External_similarity_lookup::$aStructure_APIParameters;
        $aExternalOptions = $this->sanitize( $aExternalOptions );
        return $aExternalOptions;
        
    }    
    
        /**
         * Sanitizes the unit options of the item_lookup unit type.
         * 
         * @since         1
         * @since       3           Moved from ``.
         * @return      array
         */
        protected function sanitize( array $aExternalOptions ) {
            
            // if the ISDN is spceified, the search index must be set to Books.
            if ( 
                isset( $aExternalOptions[ 'IdType' ], $aExternalOptions[ 'SearchIndex' ] )
                && 'ISBN' === $aExternalOptions[ 'IdType' ]
            ) {
                $aExternalOptions[ 'SearchIndex' ] = 'Books';
            }
            $aExternalOptions[ 'ItemId' ] =  trim( 
                $this->trimDelimitedElements( 
                    $aExternalOptions[ 'ItemId' ], 
                    ',' 
                ) 
            );
            return $aExternalOptions;
            
        }            
    

}