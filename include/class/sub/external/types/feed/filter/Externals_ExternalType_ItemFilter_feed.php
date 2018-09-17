<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */


/**
 * 
 * 
 * @since        1
 */
class Externals_ExternalType_ItemFilter_feed extends Externals_PluginUtility {
    
    /**
     * 
     */
    public $bMBStringSupported = false;

    /**
     * Sets up hooks and properties.
     */
    public function __construct() {

        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_feed_item',
            array( $this, 'replyToBlockItem' ),
            10,
            3
        );
        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_feed_item',
            array( $this, 'replyToBlockImages' ),
            10,
            3
        );

        if ( is_admin() ) {
            
            new Externals_PostMetaBox_Filter_feed(
                null, // meta box ID - null to auto-generate
                __( 'Filtering Items', 'externals' ) . ' - ' . __( 'Feed', 'externals' ),
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'normal', // context (what kind of metabox this is)
                'core' // priority             
            );
            
        }
        
        $this->bMBStringSupported = function_exists( 'mb_strpos' );
        
    }

    /**
     * @since   0.3.13
     * @return  array   The parsed item image array.
     */
    public function replyToBlockImages( $aItem, $oItem, $oArgument ) {

        $_sSubstrings = $oArgument->get( 'blacklist_image', 'src' );

        // If the option is not set, do nothing.
        if ( ! $_sSubstrings ) {
            return $aItem;
        }
        // It is possible that the item is already filtered.
        if ( empty( $aItem ) ) {
            return $aItem;
        }
        $_aSubstrings = preg_split( "/[\n\r]+/", $_sSubstrings );
        foreach( $aItem[ 'images' ] as $_sKey => $_sImageURL ) {
            if ( $this->___isBlocked( $_sImageURL, $_aSubstrings, true ) ) {
                unset( $aItem[ 'images' ][ $_sKey ] );
            }
        }
        return $aItem;

    }

    /**
     * @since   0.3.13
     * @return  array   The parsed item array.
     */
    public function replyToBlockItem( $aItem, $oItem, $oArgument ) {
        if ( $this->_isBlockedBySubString( $aItem, $oArgument, false ) ) {
            return array(); // empty item will be dropped
        }
        if ( $this->_isBlockedBySubString( $aItem, $oArgument, true ) ) {
            return array();
        }
        return $aItem;
    }
    
    /**
     * @since       1
     * @return      boolean
     * @deprecated  0.3.13
     */
    public function replyToDetermineItemIsBlocked( $bBlocked, $aItem, $oArgument ) {
        
        if ( $this->_isBlockedBySubString( $aItem, $oArgument, false ) ) {
            return true;
        }        
        if ( $this->_isBlockedBySubString( $aItem, $oArgument, true ) ) {
            return true;
        }

        return ( boolean ) $bBlocked;
        
    }
        /**
         * @return      boolean
         */
        private function _isBlockedBySubString( $aItem, $oArgument, $bBlockIfSubstringExists=true ) {
            $_aKeys = array(
                'title',
                'description',
                'content',
                'author',
                'permalink',
                // 'image',
            );        
            foreach( $_aKeys as $_sKey ) {
                $_sSubstrings = $oArgument->get( 
                    $bBlockIfSubstringExists
                        ? 'block_containing'
                        : 'block_not_containing',
                    $_sKey 
                );
                // $_aSubstrings = explode( PHP_EOL, trim( $_sSubstrings ) ); misses some cases
                $_aSubstrings = preg_split( "/[\n\r]+/", $_sSubstrings );
                if ( empty( $_aSubstrings ) ) {
                    continue;
                }
                if ( $this->___isBlocked( $aItem[ $_sKey ], $_aSubstrings, $bBlockIfSubstringExists ) ) {
                    return true;
                }
            }
            return false;
            
        }    
            /**
             * @return      boolean
             */
            private function ___isBlocked( $sSubject, array $aSubstrings, $bBlockIfSubstringExists=true ) {
                
                if ( ! $this->___isParsableScalar( $sSubject ) ) {
                    return false;
                }
                            
                $_aFunctionNames = array(
                    'strpos',
                    'mb_strpos',
                    // @tod add a checkbox for the user to decide whether it is a case sensitive or not. Also add one for regex.
                    'stripos',      
                    'mb_stripos',
                );
                
                foreach( $aSubstrings as $_sSubstring ) {
                    
                    if ( ! $this->___isParsableScalar( $_sSubstring ) ) {
                        continue;
                    }                
                    $_sFunctionName     = $_aFunctionNames[ ( integer ) $this->bMBStringSupported ];
                    $_biSubstringExists = $_sFunctionName( $sSubject, $_sSubstring );
                    
                    // @todo May need to escape some characters @see http://stackoverflow.com/questions/1531456/is-there-a-php-function-that-can-escape-regex-patterns-before-they-are-applied
                    // $_biSubstringExists = ( boolean ) preg_match( '/\Q' . $_sSubstring . '\E/si', $sSubject, $_aMatches );
                    
                    if ( $bBlockIfSubstringExists ) {
                        if ( false !== $_biSubstringExists ) {  // found
                            return true;
                        }
                    } else {
                        if ( false === $_biSubstringExists ) { // not found
                            return true;
                        }                        
                    }                    
                    
                }
                
                return false;
                
            }    
                /**
                 * @since       1
                 * @return      boolean
                 */
                private function ___isParsableScalar( $sSubject ) {
                
                    if ( ! is_scalar( $sSubject ) ) {
                        return false;
                    }
                    if ( '' === $sSubject ) {
                        return false;
                    }
                    return true;
                
                }
                
}