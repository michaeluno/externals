<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Displays the stored unit option values.
 */
class Externals_PostMetaBox_DebugInfo extends Externals_PostMetaBox_Base {
    

    /**
     * Checks whether the meta box should be registered or not in the loading page.
     */
    public function _isInThePage() {

        if ( ! parent::_isInThePage() ) {
            return false;
        }

        $_oOption = Externals_Option::getInstance();
        return $_oOption->isDebugMode();
        
    }
    
    public function content( $sOutput ) {        

        $_iPostID = Externals_WPUtility::getCurrentPostID();
        return $sOutput 
            . "<h4>" . __( 'Post ID', 'externals' ) . "</h4>"
            . "<p>" . $_iPostID . "</p>"
            . "<h4>" . __( 'External Options', 'externals' ) . "</h4>"
            . $this->oDebug->get(
                Externals_WPUtility::getPostMeta( $_iPostID )
            );
    }
    
}