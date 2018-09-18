<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides utility methods.
 * @since       1
 */
class Externals_Utility extends Externals_Utility_XML {

    /**
     * Converts characters not supported to be used in the URL query key to underscore.
     * 
     * @see         http://stackoverflow.com/questions/68651/can-i-get-php-to-stop-replacing-characters-in-get-or-post-arrays
     * @return      string      The sanitized string.
     * @sine        1
     */
    static public function getCharsForURLQueryKeySanitized( $sString ) {

        $_aSearch = array( chr( 32 ), chr( 46 ), chr( 91 ) );
        for ( $_i=128; $_i <= 159; $_i++ ) {
            array_push( $_aSearch, chr( $_i ) );
        }
        return str_replace ( $_aSearch , '_', $sString );
        
    }    

    /**
     * Returns a truncated string.
     * @since       2.2.0
     * @since       3           Moved from `AmazonAutoLinks_Utility`
     * @return      string
     */
    static public function getTruncatedString( $sString, $iLength, $sSuffix='...' ) {
        
        return ( self::getStringLength( $sString ) > $iLength )
            ? self::getSubstring( 
                    $sString, 
                    0, 
                    $iLength - self::getStringLength( $sSuffix )
                ) . $sSuffix
                // ? substr( $sString, 0, $iLength - self::getStringLength( $sSuffix ) ) . $sSuffix
            : $sString;
            
    }
    
    /**
     * Indicates whether the mb_strlen() exists or not.
     * @since       2.1.2
     * @since       3           Moved from `AmazonAutoLinks_Utility`
     */
    static private $_bFunctionExists_mb_strlen;
    
    /**
     * Returns the given string length.
     * @since       2.1.2
     * @since       3           Moved from `AmazonAutoLinks_Utility`
     */
    static public function getStringLength( $sString ) {
        
        self::$_bFunctionExists_mb_strlen = isset( self::$_bFunctionExists_mb_strlen )
            ? self::$_bFunctionExists_mb_strlen
            : function_exists( 'mb_strlen' );
        
        return self::$_bFunctionExists_mb_strlen
            ? mb_strlen( $sString )
            : strlen( $sString );        
        
    }
    
    /**
     * Indicates whether the mb_substr() exists or not.
     * @since       2.1.2
     * @since       3           Moved from `AmazonAutoLinks_Utility`
     */
    static private $_bFunctionExists_mb_substr;    
    
    /**
     * Returns the substring of the given subject string.
     * @since       2.1.2
     * @since       3           Moved from `AmazonAutoLinks_Utility`
     */
    static public function getSubstring( $sString, $iStart, $iLength=null, $sEncoding=null ) {

        self::$_bFunctionExists_mb_substr = isset( self::$_bFunctionExists_mb_substr )
            ? self::$_bFunctionExists_mb_substr
            : function_exists( 'mb_substr' ) && function_exists( 'mb_internal_encoding' );
        
        if ( ! self::$_bFunctionExists_mb_substr ) {
            return substr( $sString, $iStart, $iLength );
        }
        
        $sEncoding = isset( $sEncoding )
            ? $sEncoding 
            : mb_internal_encoding();
            
        return mb_substr( 
            $sString, 
            $iStart, 
            $iLength, 
            $sEncoding 
        );
        
    }
             
    
    /**
     * Includes the given file.
     * 
     * As it is said that include_once() is slow, let's check whether it is included by ourselves 
     * and use include().
     * 
     * @return      boolean     true on success; false on failure.
     */
    static public function includeOnce( $sFilePath ) {
            
        if ( self::hasBeenCalled( $sFilePath ) ) {
            return false;
        }
        if ( ! file_exists( $sFilePath ) ) {
            return false;
        }
        return @include( $sFilePath );                        
        
    }    
    
    /**
     * 
     * @return      string      The found character set.
     * e.g. ISO-8859-1, utf-8, Shift_JIS
     * 
     * @remark  The value set to the header charset should be case-insensitive.
     * @see     http://www.iana.org/assignments/character-sets/character-sets.xhtml
     */
    static public function getCharacterSetFromResponseHeader( $asHeaderResponse ) {
        
        $_sContentType = '';
        if ( is_string( $asHeaderResponse ) ) {
            $_sContentType = $asHeaderResponse;
        } 
        // It shuld be an array then.
        else if ( isset( $asHeaderResponse[ 'content-type' ] ) ) {
            $_sContentType = $asHeaderResponse[ 'content-type' ];
        } 
        else {
            foreach( $asHeaderResponse as $_iIndex => $_sHeaderElement ) {
                if ( false !== stripos( $_sHeaderElement, 'charset=' ) ) {
                    $_sContentType = $asHeaderResponse[ $_iIndex ];
                }
            }
        }
        
        $_bFound = preg_match(
            '/charset=(.+?)($|[;\s])/i',  // needle
            $_sContentType, // haystack
            $_aMatches
        );
        return isset( $_aMatches[ 1 ] )
            ? $_aMatches[ 1 ]
            : '';
            
    }

    /**
     * @return      string
     */
    static public function getPrefixRemoved( $sSubject, $sPrefix ) {
        
        if ( substr( $sSubject, 0, strlen( $sPrefix ) ) === $sPrefix ) {
            return substr( $sSubject, strlen( $sPrefix ) );
        }         
        return $sSubject;
        
    }


    /**
     * Trims each delimited element of the given string with the specified delimiter. 
     * 
     * $str = trimDlimitedElements( '   a , bcd ,  e,f, g h , ijk ', ',' );
     * 
     * produces:
     * 
     * 'a, bcd, e, f, g h, ijk'
     * 
     * @remark            One left white space gets added in each element to be readable.
     * @remark            Supports only one dimensional array.
     */
    static public function trimDelimitedElements( $strToFix, $strDelimiter, $fReadable=true, $fUnique=true ) {
        
        $strToFix    = ( string ) $strToFix;
        $arrElems    = self::getExploded( $strToFix, $strDelimiter );
        $arrNewElems = array();
        foreach ( $arrElems as $strElem ) {
            if ( ! is_array( $strElem ) || ! is_object( $strElem ) ) {
                $arrNewElems[] = trim( $strElem );
            }
        }
        
        if ( $fUnique ) {
            $arrNewElems = array_unique( $arrNewElems );
        }
        
        return $fReadable
            ? implode( $strDelimiter . ' ' , $arrNewElems )
            : implode( $strDelimiter, $arrNewElems );
                
    }    
        
    /**
     * Converts the given string with delimiters to a multi-dimensional array.
     * 
     * Parameters: 
     * 1: haystack string
     * 2, 3, 4...: delimiter
     * e.g. $arr = getExploded( 'a-1,b-2,c,d|e,f,g', "|", ',', '-' );
     * 
     */
    static public function getExploded() {
        
        $intArgs = func_num_args();
        $arrArgs = func_get_args();
        $strInput = $arrArgs[ 0 ];            
        $strDelimiter = $arrArgs[ 1 ];
        
        if ( ! is_string( $strDelimiter ) || $strDelimiter == '' ) return $strInput;
        if ( is_array( $strInput ) ) return $strInput;    // note that is_string( 1 ) yields false.
            
        $arrElems = preg_split( "/[{$strDelimiter}]\s*/", trim( $strInput ), 0, PREG_SPLIT_NO_EMPTY );
        if ( ! is_array( $arrElems ) ) return array();
        
        foreach( $arrElems as &$strElem ) {
            
            $arrParams = $arrArgs;
            $arrParams[0] = $strElem;
            unset( $arrParams[ 1 ] );    // remove the used delimiter.
            // now $strElem becomes an array.
            if ( count( $arrParams ) > 1 ) { // if the delimiters are gone, 
                $strElem = call_user_func_array( 
                    array( __CLASS__, 'getExploded' ), 
                    $arrParams 
                );
            }
            
            // Added this because the function was not trimming the elements sometimes... not fully tested with multi-dimensional arrays. 
            if ( is_string( $strElem ) )
                $strElem = trim( $strElem );
            
        }

        return $arrElems;

    }     
        /**
         * @deprecated      Use `getExploded()` instead.
         */
        static public function convertStringToArray() {
            $_aParams = func_get_args();    
            return call_user_func_array(
                array( __CLASS__, 'getExploded' ),
                $_aParams
            );
        }
    
    
}