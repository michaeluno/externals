<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box that contains Template options.
 */
class Externals_PostMetaBox_Template extends Externals_PostMetaBox_Base {
        
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
        
        $_oFields = new Externals_FormFields_External_Template;
        $_aFields = $_oFields->get( 
            '_',     // field id prefix
            $this->_getExternalType()  // external type
        );
        foreach( $_aFields as $_aField ) {           
            $this->addSettingFields( $_aField );
        }
            
    }
    
    /**
     * Validates submitted form data.
     */
    public function validate( $aInput, $aOriginal, $oFactory ) {    
        
        // Schedule pre-fetch for the unit if the options have been changed.
        if ( $aInput !== $aOriginal ) {
            Externals_Event_Scheduler::prefetch( 
                Externals_PluginUtility::getCurrentPostID()
            );
        }
        
        return $aInput;
        
    }

}