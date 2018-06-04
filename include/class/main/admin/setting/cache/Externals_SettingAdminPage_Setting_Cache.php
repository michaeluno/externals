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
 * Adds the 'Cache' tab to the 'Settings' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_SettingAdminPage_Setting_Cache extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        // Form sections
        new Externals_SettingAdminPage_Setting_Cache_Cache( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'section_id'    => 'cache',
                'tab_slug'      => $this->sTabSlug,
                'title'         => __( 'Caches', 'externals' ),
                // 'description'   => array(
                    // __( 'Set the criteria to filter fetched items.', 'externals' ),
                // ),
            )
        );
        
    }

    public function replyToDoTab( $oFactory ) {
        echo "<div class='right-submit-button'>"
                . get_submit_button()  
            . "</div>";
    }    
        
}
