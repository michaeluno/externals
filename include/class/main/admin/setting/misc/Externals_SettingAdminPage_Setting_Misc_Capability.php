<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 */

/**
 * Adds the 'Capability' form section to the 'Misc' tab.
 * 
 * @since       1
 */
class Externals_SettingAdminPage_Setting_Misc_Capability extends Externals_AdminPage_Section_Base {
    
    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    protected function construct( $oFactory ) {}
    
    /**
     * Adds form fields.
     * @since       1
     * @return      void
     */
    public function addFields( $oFactory, $sSectionID ) {

        $oFactory->addSettingFields(
            $sSectionID, // the target section id    
            array(
                'field_id'      => 'setting_page_capability',
                'type'          => 'select',
                'title'         => __( 'Capability', 'externals' ),
                'description'   => __( 'Select the user role that is allowed to access the plugin setting pages.', 'externals' )
                    . __( 'Default', 'externals' ) . ': ' . __( 'Administrator', 'externals' ),
                'capability'    => 'manage_options',
                'label'         => array(                        
                    'manage_options' => __( 'Administrator', 'externals' ),
                    'edit_pages'     => __( 'Editor', 'externals' ),
                    'publish_posts'  => __( 'Author', 'externals' ),
                    'edit_posts'     => __( 'Contributor', 'externals' ),
                    'read'           => __( 'Subscriber', 'externals' ),
                ),
            )
        );
    }
        
    
    /**
     * Validates the submitted form data.
     * 
     * @since       1
     */
    public function validate( $aInput, $aOldInput, $oAdminPage, $aSubmitInfo ) {
    
        $_bVerified = true;
        $_aErrors   = array();
        
          
        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oAdminPage->setFieldErrors( $_aErrors );     
            $oAdminPage->setSettingNotice( __( 'There was something wrong with your input.', 'externals' ) );
            return $aOldInput;
        }
                
        return $aInput;     
        
    }
   
}