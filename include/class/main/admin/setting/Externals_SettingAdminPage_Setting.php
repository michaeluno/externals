<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the `Settings` page.
 * 
 * @since       1
 */
class Externals_SettingAdminPage_Setting extends Externals_AdminPage_Page_Base {


    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    public function construct( $oFactory ) {
        
        // Tabs
        new Externals_SettingAdminPage_Setting_General( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'general',
                'title'     => __( 'General', 'externals' ),
            )
        );
        new Externals_SettingAdminPage_Setting_Cache( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'cache',
                'title'     => __( 'Cache', 'externals' ),
            )
        );        
        new Externals_SettingAdminPage_Setting_Misc( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'misc',
                'title'     => __( 'Misc', 'externals' ),
            )
        );        
        new Externals_SettingAdminPage_Setting_Reset( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'reset',
                'title'     => __( 'Reset', 'externals' ),
            )
        );
       

    }   
    
    /**
     * Prints debug information at the bottom of the page.
     */
    public function replyToDoAfterPage( $oFactory ) {
            
        $_oOption = Externals_Option::getInstance();
        if ( ! $_oOption->isDebugMode() ) {
            return;
        }
        echo "<h3 style='display:block; clear:both;'>" 
                . __( 'Debug Info', 'externals' ) 
            .  "</h3>";
        $oFactory->oDebug->dump( $oFactory->getValue() );
        
    }
        
}
