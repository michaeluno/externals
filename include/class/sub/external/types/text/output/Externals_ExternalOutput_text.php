<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates Amazon product links by ItemSearch.
 * 
 * @package         Externals
 */
class Externals_ExternalOutput_text extends Externals_ExternalOutput_Base {
    
    /**
     * Stores the unit type.
     * @remark      Note that the base constructor will create a unit option object based on this value.
     */    
    public $sExternalType = 'text';
    
    /**
     * Represents the array structure of the item array element of API response data.
     * @since            unknown
     */    
    public static $aStructure_Item = array(
    );
    
    /**
     * 
     * @return    array    The response array.
     */
    public function getItems( $aURLs=array() ) {
 
        $_aResponses = parent::getItems( $aURLs );
        $_aResponses = $this->_getContentsByLineNumbers( $_aResponses );                  
        return $_aResponses;
        
    }
        /**
         * @return      array
         * @since       1
         */
        private function _getContentsByLineNumbers( $aItems ) {
            
            $_iStart  = ( integer ) $this->oArgument->get( 'start' );
            $_iEnd    = ( integer ) $this->oArgument->get( 'end' );
            
            // If not set, return intact.
            if ( 1 >= $_iStart && 0 >= $_iEnd ) {
                return $aItems;
            }
            
            // Convert 'end' to length
            $_inLength  = 0 >= $_iEnd
                ? null
                : $_iEnd - $_iStart + 1;
            
            foreach( $aItems as $_isIndex => $_sText ) {
                $_aLines = preg_split( "/[\n\r]+/", $_sText ); // explode( PHP_EOL, $_sText ) will miss some cases
                $_iLines = count( $_aLines );          
                $_aLines = array_slice( 
                    $_aLines, 
                    $_iStart - 1,   // convert it to zero-base
                    $_inLength      // null or integer
                );                           
                $aItems[ $_isIndex ] = implode( PHP_EOL, $_aLines );
            }
            return $aItems;
            
        }
    
}