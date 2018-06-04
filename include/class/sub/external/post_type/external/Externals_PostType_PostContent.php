<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides method to render post contents in a single post page.
 * 
 * @package     Externals
 * @since       1
 * 
 */
class Externals_PostType_PostContent extends Externals_PostType_ListTable {
    
    /**
     * Prints out the fetched product links.
     * 
     * @remark      Used for the post type single page that functions as preview the result.
     * @since       1       Changed the name from `_replytToPrintPreviewProductLinks()`.
     * */
    public function content( $sContent ) {
    
        $_oOption = Externals_Option::getInstance();
        if ( ! $_oOption->isPreviewVisible() ) {
            return $sContent;
        }
        
        $_sExternalType = $this->_getExternalType( $GLOBALS[ 'post' ]->ID );
        $_sExternalArgumentClassName = "Externals_ExternalArgument_" . $_sExternalType;
        $_oExternalArguments         = new $_sExternalArgumentClassName( $GLOBALS['post']->ID );
        $_aExternalArguments         = $_oExternalArguments->get();
        return $sContent 
            . Externals_Output::getInstance( $_aExternalArguments )->get();

    }    
   
        private function _getExternalType( $iPostID ) {
            $_sExternalType            = get_post_meta(
                $GLOBALS[ 'post' ]->ID,
                '_type',
                true
            );
            $_sExternalType            = $_sExternalType 
                ? $_sExternalType 
                : 'text';  
            return $_sExternalType;                
        }   
   
}