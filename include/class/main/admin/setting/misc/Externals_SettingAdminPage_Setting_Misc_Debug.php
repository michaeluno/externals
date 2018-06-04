<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 */

/**
 * Adds the 'Debug' form section to the 'Misc' tab.
 * 
 * @since       1
 */
class Externals_SettingAdminPage_Setting_Misc_Debug extends Externals_AdminPage_Section_Base {
    
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
                'field_id'      => 'debug_mode',
                'type'          => 'radio',
                'title'         => __( 'Debug Mode', 'externals' ),
                'capability'    => 'manage_options',
                'label'         => array(
                    1 => __( 'On', 'externals' ),
                    0 => __( 'Off', 'externals' ),
                ),
                'default'       => 0,
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