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
 * Adds the 'Misc' tab to the 'Settings' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_SettingAdminPage_Setting_Misc extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        // Form sections
        new Externals_SettingAdminPage_Setting_Misc_Capability( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'tab_slug'      => $this->sTabSlug,
                'section_id'    => 'capabilities',       // avoid hyphen(dash), dots, and white spaces
                'capability'    => 'manage_options',
                'title'         => __( 'Access Rights', 'externals' ),
                'description'   => array(
                    __( 'Set the access levels to the plugin setting pages.', 'externals' ),
                ),
            )
        );           
/*         new Externals_SettingAdminPage_Setting_Misc_FormOption( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'tab_slug'      => $this->sTabSlug,
                'section_id'    => 'form_options',       // avoid hyphen(dash), dots, and white spaces
                'capability'    => 'manage_options',
                'title'         => __( 'Form', 'externals' ),
                'description'   => array(
                    __( 'Set allowed HTML tags etc.', 'externals' ),
                ),
            )
        );     */    
        new Externals_SettingAdminPage_Setting_Misc_Debug( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'tab_slug'      => $this->sTabSlug,
                'section_id'    => 'debug', 
                'capability'    => 'manage_options',
                'title'         => __( 'Debug', 'externals' ),
                'description'   => array(
                    __( 'For developers who need to see the internal workings of the plugin.', 'externals' ),
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
