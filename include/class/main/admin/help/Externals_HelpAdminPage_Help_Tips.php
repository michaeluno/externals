<?php
/**
 * Externals
 * 
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds an in-page tab to an admin page.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_HelpAdminPage_Help_Tips extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     * 
     * @callback        action      load_{page slug}_{tab slug}
     */
    public function replyToLoadTab( $oFactory ) {
        
        // $oFactory->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/admin.css' ) );
    }
    
    /**
     * 
     * @callback        action      do_{page slug}_{tab slug}
     */
    public function replyToDoTab( $oFactory ) {

        $_oWPReadmeParser = new Externals_AdminPageFramework_WPReadmeParser( 
            Externals_Registry::$sDirPath . '/readme.txt'
        );    
        echo "<h3>" . __( 'Other Notes', 'externals' ) . "</h3>"
            . $_oWPReadmeParser->getSection( 'Other Notes' );    
            
       
    
    }    
            
}