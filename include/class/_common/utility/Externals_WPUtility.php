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
class Externals_WPUtility extends Externals_WPUtility_Post {
    
    /**
     * Returns the readable date-time string.
     */
    static public function getSiteReadableDate( $iTimeStamp, $sDateTimeFormat=null, $bAdjustGMT=false ) {
                
        static $_iOffsetSeconds, $_sDateFormat, $_sTimeFormat;
        $_iOffsetSeconds = $_iOffsetSeconds 
            ? $_iOffsetSeconds 
            : get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        $_sDateFormat = $_sDateFormat
            ? $_sDateFormat
            : get_option( 'date_format' );
        $_sTimeFormat = $_sTimeFormat
            ? $_sTimeFormat
            : get_option( 'time_format' );    
        $sDateTimeFormat = $sDateTimeFormat
            ? $sDateTimeFormat
            : $_sDateFormat . ' ' . $_sTimeFormat;
        
        if ( ! $iTimeStamp ) {
            return 'n/a';
        }
        $iTimeStamp = $bAdjustGMT ? $iTimeStamp + $_iOffsetSeconds : $iTimeStamp;
        return date_i18n( $sDateTimeFormat, $iTimeStamp );
            
    }    
    
    /**
     * Converts a given string into a specified character set.
     * @since       3
     * @return      string      The converted string.
     * @see         http://php.net/manual/en/mbstring.supported-encodings.php
     * @param       string          $sText                      The subject text string.
     * @param       string          $sCharSetTo                 The character set to convert to.
     * @param       string|boolean  $bsCharSetFrom              The character set to convert from. If a character set is not specified, it will be auto-detected.
     * @param       boolean         $bConvertToHTMLEntities     Whether or not the string should be converted to HTML entities.
     */
    static public function convertCharacterEncoding( $sText, $sCharSetTo='', $bsCharSetFrom=true, $bConvertToHTMLEntities=false ) {
        
        if ( ! function_exists( 'mb_detect_encoding' ) ) {
            return $sText;
        }
        
        $sCharSetTo = $sCharSetTo
            ? $sCharSetTo
            : get_bloginfo( 'charset' );
        
        $_bsDetectedEncoding = $bsCharSetFrom && is_string( $bsCharSetFrom )
            ? $bsCharSetFrom
            : self::getDetectedCharacterSet(
                $sText,
                $bsCharSetFrom              
            );
        $sText = false !== $_bsDetectedEncoding
            ? mb_convert_encoding( 
                $sText, 
                $sCharSetTo, // encode to
                $_bsDetectedEncoding // from
            )
            : mb_convert_encoding( 
                $sText, 
                $sCharSetTo // encode to      
                // auto-detect
            );
        
        if ( $bConvertToHTMLEntities ) {            
            $sText  = mb_convert_encoding( 
                $sText, 
                'HTML-ENTITIES', // to
                $sCharSetTo  // from
            );
        }
        
        return $sText;
        
    }
    /**
     * 
     * @return      boolean|string      False when not found. Otherwise, the found encoding character set.
     */
    static public function getDetectedCharacterSet( $sText, $sCandidateCharSet='' ) {
        
        $_aEncodingDetectOrder = array(
            get_bloginfo( 'charset' ),
            "auto",
        );
        if ( is_string( $sCandidateCharSet ) && $sCandidateCharSet ) {
            array_unshift( $_aEncodingDetectOrder, $sCandidateCharSet );
        }        

        // Returns false or the found encoding character set
        return mb_detect_encoding( 
            $sText, // subject string
            $_aEncodingDetectOrder, // candidates
            true // strict detection - true/false
        );
        
    }
    
    /**
     * Redirect the user to a post definition edit page.
     * @sine        1
     * @return      void
     */
    static public function goToPostDefinitionPage( $iPostID, $sPostType, array $aGET=array() ) {
        exit( 
            wp_redirect( 
                self::getPostDefinitionEditPageURL( 
                    $iPostID, 
                    $sPostType, 
                    $aGET 
                )
            ) 
        );        
    }
    
    /**
     * Returns a url of a post definition edit page.
     * @return      string
     */
    static public function getPostDefinitionEditPageURL( $iPostID, $sPostType, array $aGET=array() ) {
         // e.g. http://.../wp-admin/post.php?post=196&action=edit&post_type=my_post_type
        return add_query_arg( 
            array( 
                'action'    => 'edit',
                'post'      => $iPostID,
            ) + $aGET, 
            admin_url( 'post.php' ) 
        );
    }
    
    
    /**
     * Deletes transient items by prefix of a transient key.
     * 
     * @since   1
     * @remark  for the deactivation hook. Also used by the Clear Caches submit button.
     */
    public static function cleanTransients( $asPrefixes=array( 'ETN' ) ) {    

        // This method also serves for the deactivation callback and in that case, an empty value is passed to the first parameter.
        $_aPrefixes = self::getAsArray( $asPrefixes );
        
        foreach( $_aPrefixes as $_sPrefix ) {
            $GLOBALS['wpdb']->query( "DELETE FROM `" . $GLOBALS['table_prefix'] . "options` WHERE `option_name` LIKE ( '_transient_%{$_sPrefix}%' )" );
            $GLOBALS['wpdb']->query( "DELETE FROM `" . $GLOBALS['table_prefix'] . "options` WHERE `option_name` LIKE ( '_transient_timeout_%{$_sPrefix}%' )" );
        }
    
    }


}