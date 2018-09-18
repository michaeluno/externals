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
class Externals_ExternalOutput_feed extends Externals_ExternalOutput_Base {
    
    /**
     * Stores the unit type.
     * @remark      Note that the base constructor will create a unit option object based on this value.
     */    
    public $sExternalType = 'feed';
    
    /**
     * Represents the array structure of the item array element of API response data.
     * @since            unknown
     */    
    public static $aStructure_Item = array();
    
    public $sSiteDateFormat = '';
    public $sSiteTimeFormat = '';    
    
    public function construct() {
        
        $this->sSiteDateFormat     = get_option( 'date_format' );
        $this->sSiteTimeFormat     = $this->sSiteDateFormat . ' g:i a';
        
    }
    
    
    /**
     * 
     * @return    array    The response array.
     */
    public function getItems( $aURLs=array() ) {

        $aURLs = $this->_getURLsSanitized( $aURLs );
        
        add_filter( 
            Externals_Registry::HOOK_SLUG . '_filter_simiplepie_http_arguments',
            array( $this, 'replyToSetCustomHTTPArguments' )
        );
 
        $_oFeed = new Externals_SimplePie();
        $_oFeed->set_sortorder( $this->oArgument->get( 'sort' ) );
        $_oFeed->set_feed_url( $aURLs );
        $_oFeed->init();
        
        // $_oFeed = fetch_feed( $aURLs );
        
        remove_filter( 
            Externals_Registry::HOOK_SLUG . '_filter_simiplepie_http_arguments',
            array( $this, 'replyToSetCustomHTTPArguments' )
        );
        
        $_iTimezoneOffset = ( integer ) $this->oArgument->get( 'source_timezone' );
        $_iCount          = ( integer ) $this->oArgument->get( 'count' ) ;

        $_iAdded = 0;
        $_aItems = array();
        foreach( $_oFeed->get_items() as $_iIndex => $_oItem ) {
            
            if ( -1 !== $_iCount && $_iCount <= $_iAdded ) {
                break;
            }

            $_sID   = $_oItem->get_id();
            $_aItem = $this->___getItem( $_oItem, $_sID, $_iTimezoneOffset );
                        
            // Black listed items will be dropped through the above filter.
            if ( empty( $_aItem ) ) {
                continue;
            }            

            // Storing the item with the key of ID prevents duplicates.
            $_aItems[ $_sID ] = $_aItem;
            $_iAdded++;
            
        }

        return $_aItems;
        
    }

        /**
         * @param $oItem
         * @return  array
         */
        private function ___getItem( SimplePie_Item $oItem, $sID, $iTimezoneOffset ) {

            $_nsDescription = $oItem->get_description();
            $_nsContent     = $oItem->get_content();
            $_oThisFeed     = $oItem->get_feed();
            $_aItem = array(
                'id'            => $sID,   // guid
                'title'         => $oItem->get_title(),
                'date'          => $this->_getItemDate( $oItem, $iTimezoneOffset ),
                'author'        => $this->_getItemAuthor( $oItem ),
                'permalink'     => $oItem->get_permalink(),
                'description'   => $_nsDescription,
                'content'       => $_nsContent,
                'images'        => $this->_getImages( $_nsContent ? $_nsContent : $_nsDescription ),
                'source'        => $oItem->get_base(),
                'source_feed'   => $_oThisFeed->subscribe_url(),
            );
    
            if ( $_oEnclosure = $oItem->get_enclosure() ) {
                $_aItem[ 'description' ] .= $_oEnclosure->get_description();
                $_sImageURL = $_oEnclosure->get_thumbnail();
                $_aItem[ 'images' ][ $_sImageURL ] = $_sImageURL;
                if ( false !== strpos( $_oEnclosure->get_type(), 'image' ) ) {
                    $_sImageURL = $_oEnclosure->get_link();
                    $_aItem[ 'images' ][ $_sImageURL ] = $_sImageURL;
                }
            }
                        
            return apply_filters(
                Externals_Registry::HOOK_SLUG . '_filter_feed_item',
                $_aItem,
                $oItem, // 0.3.12+
                $this->oArgument    // 0.3.13+
            );            
               
        }    
    
        /**
         * @since       1
         * @return      string
         */
        private function _getItemAuthor( $oFeedItem ) {
            
            $_sAuthor = $oFeedItem->get_author();
            if ( $this->_isMD5( $_sAuthor ) ) {
                return '';
            }
            return $_sAuthor;

        }
            private function _isMD5( $sString ) {
                if ( empty( $sString ) ) {
                    return false;
                }
                return preg_match( '/^[a-f0-9]{32}$/', $sString );
            }        
        /**
         * @return      array
         * @since       1
         */
        private function _getURLsSanitized( $aURLs ) {
            
            $_aSanitized = array();
            foreach( $aURLs as $_sURL ) {
                
                if ( ! filter_var( $_sURL, FILTER_VALIDATE_URL ) ) {
                    continue;
                }
                $_aSanitized[] = $_sURL;
                
            }
            return $_aSanitized;
            
        }    
        
        /**
         * @since       1
         * @return      string
         */
        private function _getItemDate( $oFeedItem, $iHourOffset ) {
            
            $_iSecondOffset   = $iHourOffset * 60 * 60;
            return date( 
                $this->sSiteTimeFormat, // date/time format
                $oFeedItem->get_date( 'U' ) + $_iSecondOffset // time-stamp
            );

        }
        /**
         * @return      array
         */
        private function _getImages( $sContent ) {
            
            $_oDoc    = $this->oDOM->loadDOMFromHTMLElement( $sContent );
            $_oIMGs   = $_oDoc->getElementsByTagName( 'img' );
            $_aOutput = array();
            foreach( $_oIMGs as $_oIMG ) {
                $_sURL      = $_oIMG->getAttribute( 'src' );
                if ( ! $_sURL ) {
                    continue;
                }
                if ( false === filter_var( $_sURL, FILTER_VALIDATE_URL ) ) {
                    continue;
                }
                $_aOutput[ $_sURL ] = $_sURL;
            }
            return $_aOutput;
            
        }    

 
    /**
     * @since       1
     * @return      array
     */
    public function replyToSetCustomHTTPArguments( $aHTTPArguments ) {
        
        return array(
                'cache_duration' => $this->oArgument->getCacheDuration(),
            )
            + $this->oArgument->get() 
            + $aHTTPArguments
            ;
        
    }
    
}