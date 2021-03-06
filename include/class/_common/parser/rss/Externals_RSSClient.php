<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Reads, loads and saves HTML documents.
 * 
 * It has a caching system built-in.
 * 
 * @since       1       
 */
class Externals_RSSClient extends Externals_PluginUtility {
    
    
    /**
     * Supported sort order slugs.
     * 'date_ascending'
     * 'date_decending' (default) 
     * 'title_ascending'
     * 'title_decending'
     * 'random' 
     */
    public $sSortOrder = 'date_decending';
    
    /**
     * Sets up properties
     */
    public function __construct( $asURLs, $iCacheDuration=86400 ) {
        
        $this->aURLs = $this->getAsArray( $asURLs );
        $this->iCacheDuration = $iCacheDuration;
        
    }

    public function get() {
        
        $_oHTTP     = new Externals_HTTPClient(
            $this->aURLs,
            $this->iCacheDuration,
            null, // arguments
            'rss'   // response type - this just leaves a mark in the database table.
        );  

        $_aItems = array();
        foreach( $_oHTTP->get() as $_sHTTPBody ) {
            $_aItems = array_merge(
                $_aItems,
                $this->_getRSSItems( $_sHTTPBody )
            );
        }
        
        $this->_sort( $_aItems );
        return $_aItems;
                            
    }
        protected function _sort( &$aItems ) {
            if ( 'random' === $this->sSortOrder ) {
                shuffle( $aItems );
                return;
            }
            usort( 
                $aItems, 
                array( $this, 'replyToSortBy_'. $this->sSortOrder ) 
            );
        }
            /**
             * 
             * @callback    function        usort
             */
            public function replyToSortBy_date_ascending( $aA, $aB ) {
                return strtotime( $aA[ 'pubDate' ] ) - strtotime( $aB[ 'pubDate' ] );
            }
            public function replyToSortBy_date_decending( $aA, $aB ) {
                return strtotime( $aB[ 'pubDate' ] ) - strtotime( $aA[ 'pubDate' ] );
            }            
            public function replyToSortBy_title_ascending( $aA, $aB ) {
                
                $_sTitle_A = apply_filters(
                    'externals_filter_external_product_raw_title', 
                    $aA[ 'title' ]
                );
                $_sTitle_B = apply_filters(
                    'externals_filter_external_product_raw_title', 
                    $aB[ 'title' ]
                );
                return strnatcasecmp( $_sTitle_A, $_sTitle_B );
                
            }               
            public function replyToSortBy_title_decending( $aA, $aB ) {

                $_sTitle_A = apply_filters(
                    'externals_filter_external_product_raw_title', 
                    $aA[ 'title' ]
                );
                $_sTitle_B = apply_filters(
                    'externals_filter_external_product_raw_title', 
                    $aB[ 'title' ]
                );
                return strnatcasecmp( $_sTitle_B, $_sTitle_A );    
            }               
   
        /**
         * 
         * @return      array
         */
        private function _getRSSItems( $_sHTTPBody ) {
   
            $_boXML = $this->getXMLObject( 
                $_sHTTPBody, 
                false // do not strip HTML/XML tags
            );            
            if ( false === $_boXML ) {
                return array();
            }
            $_oXML   = $_boXML;
            $_aXML   = $this->convertXMLtoArray( $_oXML );

            $_aItems = $this->getElement(
                $_aXML,
                array( 'channel', 'item' )
            );
            return $this->isAssociative( $_aItems )
                ? array( 0 => $_aItems )
                : $_aItems;
   
        }

}