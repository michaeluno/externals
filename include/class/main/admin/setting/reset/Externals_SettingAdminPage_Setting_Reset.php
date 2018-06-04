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
 * Adds the 'Rest' tab to the 'Settings' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_SettingAdminPage_Setting_Reset extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        // Form sections
        new Externals_SettingAdminPage_Setting_Reset_RestSettings( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'tab_slug'      => $this->sTabSlug,
                'section_id'    => 'reset_settings',
                'title'         => __( 'Reset Settings', 'externals' ),
                'description'   => array(
                    __( 'If you get broken options, initialize them by performing reset.', 'externals' ),
                ),
            )
        );           
     
    }
    
    public function replyToDoTab( $oFactory ) {
        echo "<div class='right-submit-button'>"
                . get_submit_button()  
            . "</div>";
    }    
            
}
