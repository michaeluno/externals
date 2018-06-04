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
class Externals_HelpAdminPage_Help_Support extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     * 
     * @callback        action      load_{page slug}_{tab slug}
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        
    }
    
    /**
     * 
     * @callback        action      do_{page slug}_{tab slug}
     */
    public function replyToDoTab( $oFactory ) {
        
        echo "<h3>" 
                . __( 'Support Forum', 'externals' )
            . "</h3>";
        echo "<p>"
            . sprintf( 
                __( 'To get free support, visit the <a href="%1$s" target="_blank">support forum</a>.', 'externals' ),
                'https://wordpress.org/support/plugin/externals'
            )
            . "</p>";
            
    }    
            
}