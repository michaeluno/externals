<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides methods that uses WordPress built-in functions.
 * @since       1       
 */
class Externals_WPUtility_Path extends Externals_Utility {
   
    /**
     * Checks multiple file existence.
     * 
     * @return      boolean
     */
    static public function doFilesExist( $asFilePaths ) {        
        foreach( self::getAsArray( $asFilePaths ) as $_sFilePath ) {
            if ( ! file_exists( $_sFilePath ) ) {
                return false;
            }
        }                
        return true;
    }   
   
    /**
     * Calculates the absolute path from the given relative path to the WordPress installed directory.
     * 
     * @since       1
     * @return      string
     */
    static public function getAbsolutePathFromRelative( $sRelativePath ) {
        
        // APSPATH has a trailing slash.
        return ABSPATH . self::getPathSanitized( $sRelativePath );
        
    }
    
    static public function getPathSanitized( $sPath ) {

        // removes the heading ./ or .\ 
        $sPath  = preg_replace( "/^\.[\/\\\]/", '', $sPath, 1 );    
        
        // removes the leading slash and backslashes.
        $sPath  = ltrim( $sPath, '/\\' ); 
        
        // Use all forward slashes
        $sPath = str_replace( '\\', '/', $sPath );
        
        return $sPath;
        
    }
        
    
   
    /**
     * Returns the file path by checking if the given path is a file.
     * 
     * If fails, it attempts to check with the relative path to ABSPATH.
     * 
     * This is necessary when some users build the WordPress site locally and immigrate to the production site.
     * In that case, the stored absolute path won't work so it needs to be converted to the one that works in the new environment.
     * 
     * @since       1
     */
    public static function getReadableFilePath( $sFilePath, $sRelativePathToABSPATH='' ) {
        
        if ( @file_exists( $sFilePath ) ) {
            return $sFilePath;
        }
        
        if ( ! $sRelativePathToABSPATH ) {
            return false;
        }
        
        // try with the relative path.
        $_sAbsolutePath = realpath( trailingslashit( ABSPATH ) . $sRelativePathToABSPATH );
        if ( ! $_sAbsolutePath ) {
            return false;
        }
        
        if ( @file_exists( $_sAbsolutePath ) ) {
            return $_sAbsolutePath;
        }
        
        return false;        
        
    }    

    /**
     * Calculates the URL from the given path.
     * 
     * @static
     * @access           public
     * @return           string            The source url
     * @since        1
     * @since        1
     */
    static public function getSRCFromPath( $sFilePath ) {
                        
        $_oWPStyles     = new WP_Styles();    // It doesn't matter whether the file is a style or not. Just use the built-in WordPress class to calculate the SRC URL.
        $_sRelativePath = Externals_Utility::getRelativePath( ABSPATH, $sFilePath );       
        $_sRelativePath = preg_replace( "/^\.[\/\\\]/", '', $_sRelativePath, 1 ); // removes the heading ./ or .\ 
        $sHref          = trailingslashit( $_oWPStyles->base_url ) . $_sRelativePath;
        return esc_url( $sHref );

    }    
    
}