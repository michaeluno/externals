<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides utility methods that uses WordPerss built-in functions.
 *
 * @package     Externals
 * @since       1
 */
class Externals_WPUtility_Post extends Externals_WPUtility_Path {
    
    /**
     * Attempts to find a current post iD.
     * 
     * @return      integer
     */
    static public function getCurrentPostID() {

        // When editing a post, usually this is available.
        if ( isset( $_GET[ 'post' ] ) ) {
            return $_GET[ 'post' ];
        }
    
        // It is set when the user send the form in post.php.
        if ( isset( $_POST[ 'post_ID' ] ) ) {
            return $_POST[ 'post_ID' ];
        }
        
        // This also shouold be available.
        if ( isset( $GLOBALS[ 'post' ], $GLOBALS[ 'post' ]->ID ) ) {
            return $GLOBALS[ 'post' ]->ID;
        }
        
        return 0;
        
    }    
    
    /**
     * @return      array|string       If no key is specified, an associative array holding meta values of the specified post by post ID. 
     * If a meta key is specified, it returns the value of the meta.
     */
    static public function getPostMeta( $iPostID, $sKey='', $sPrefixToRemove='' ) {
        
        // If a key is specified, return a value.
        if ( $sKey ) {
            // return self::getPostMetaWithCache(
                // $iPostID, 
                // $sKey            
            // );
            return get_post_meta( 
                $iPostID, 
                $sKey, 
                true 
            );       
        }

        $_aPostMeta = array();        
        
        // There are cases that post id is not set, called from the constructor of a unit option class
        // only to use the format method.
        if ( ! $iPostID ) {
            return $_aPostMeta;
        }

        // $_aMetaKeys = self::getAsArray( get_post_custom_keys( $iPostID ) );            
        $_aMetaKeys = self::getPostCustomKeys( $iPostID );
        foreach( $_aMetaKeys  as $_sKey ) {
            $_sMetaKey = $_sKey;
            $_sKey = $sPrefixToRemove
                ? self::getPrefixRemoved( $_sKey, $sPrefixToRemove )
                : $_sKey;
            $_aPostMeta[ $_sKey ] = get_post_meta( 
                $iPostID, 
                $_sMetaKey, 
                true 
            );        
            // $_aPostMeta[ $_sKey ] = self::getPostMetaWithCache(
                // $iPostID, 
                // $_sMetaKey            
            // );
        }
        return $_aPostMeta;                
        
    }
        /**
         * @return      array
         * @since       1
         */
        static private function getPostCustomKeys( $iPostID ) {

            if ( isset( self::$_aPostCustomKeysCaches[ $iPostID ] ) ) {
                return self::$_aPostCustomKeysCaches[ $iPostID ];
            }
            self::$_aPostCustomKeysCaches[ $iPostID ] = self::getAsArray( get_post_custom_keys( $iPostID ) );
            return self::$_aPostCustomKeysCaches[ $iPostID ];
            
        }
            static private $_aPostCustomKeysCaches = array();
        /**
         * @since       1
         */
        static private function getPostMetaWithCache( $iPostID, $sKey ) {
            if ( isset( $_aPostMetaCaches[ $iPostID ][ $sKey ] ) ) {
                return $_aPostMetaCaches[ $iPostID ][ $sKey ];
            }
            if ( ! isset( $_aPostMetaCaches[ $iPostID ] ) ) {
                $_aPostMetaCaches[ $iPostID ] = array();
            }
            $_aPostMetaCaches[ $iPostID ][ $sKey ] = get_post_meta( 
                $iPostID, 
                $sKey,
                true 
            );                   
            return $_aPostMetaCaches[ $iPostID ][ $sKey ];
        }
            static public $_aPostMetaCaches = array();

    /**
     * Returns a form field label array listing post titles.
     * @return      array
     * @since       1
     */
    static public function getPostTitles( $sPostTypeSlug ) {

        $_aLabels = array();
        $_oQuery  = new WP_Query(
            array(
                'post_status'    => 'publish',    
                'post_type'      => $sPostTypeSlug,
                'posts_per_page' => -1, // ALL posts
            )
        );            
        foreach( $_oQuery->posts as $_oPost ) {
            $_aLabels[ $_oPost->ID ] = $_oPost->post_title;
        }
        return $_aLabels;
        
    }      
    
    /**
     * Creates a post.
     * 
     * @since       1
     * @return      integer     The created post ID.
     */
    static public function createPost( $sPostTypeSlug, array $aPostColumns=array(), array $aPostMeta=array(), array $aTaxonomy=array() ) {
        
        $_iPostID = wp_insert_post(
            $aPostColumns
            + array(
                'comment_status'    => 'closed',
                'ping_status'       => 'closed',
                'post_author'       =>  get_current_user_id(),
                'post_title'        => '',
                'post_status'       => 'publish',
                'post_type'         => $sPostTypeSlug,
                'tax_input'         => $aTaxonomy 
            )        
        );
        
        // Add meta fields.
        if ( $_iPostID ) {
            self::updatePostMeta( 
                $_iPostID, 
                $aPostMeta 
            );
        }
                
        return $_iPostID;        
        
    }    
    
    /**
     * Updates post meta data.
     * 
     * @since       1
     */
    public static function updatePostMeta( $iPostID, array $aPostData ) {
        foreach( $aPostData as $_sFieldID => $_mValue ) {
            update_post_meta( 
                $iPostID, 
                $_sFieldID, 
                $_mValue 
            );
        }
    }   

}