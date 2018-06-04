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
 * Adds the 'General' tab to the 'Settings' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_SettingAdminPage_Setting_General extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        // Form sections
        new Externals_SettingAdminPage_Setting_General_ExternalPreview( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'section_id'    => 'external_preview',
                'tab_slug'      => $this->sTabSlug,
                'title'         => __( 'External Preview', 'externals' ),
            )
        );             
        
    }
    
    public function replyToDoTab( $oFactory ) {
        echo "<div class='right-submit-button'>"
                . get_submit_button()  
            . "</div>";
    }
            
}
