<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box added to the category unit definition page.
 */
class Externals_MetaBox_CategoryExternal_Main extends Externals_PostMetaBox_Base {
    
    /**
     * Stores the unit type slug(s). 
     */    
    protected $aExternalTypes = array( 'category' );
    
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
        
        $_oFields = new Externals_FormFields_CategoryExternal_BasicInformation;
        foreach( $_oFields->get() as $_aField ) {           
            $this->addSettingFields( $_aField );
        }
                    
    }
    
    /**
     * Validates submitted form data.
     */
    public function validate( $aInput, $aOriginal, $oFactory ) {    
        
        // Formats the options
        $_oCategoryExternalOption = new Externals_ExternalArgument_category(
            null,
            $aInput
        );
        $_aFormatted = $_oCategoryExternalOption->get();
        
        // Drop unsent keys.
        foreach( $_aFormatted as $_sKey => $_mValue ) {
            if ( ! array_key_exists( $_sKey, $aInput ) ) {
                unset( $_aFormatted[ $_sKey ] );
            }
        }
        
        // Schedule pre-fetch for the unit if the options have been changed.
        if ( $aInput !== $aOriginal ) {
            Externals_Event_Scheduler::prefetch(
                Externals_PluginUtility::getCurrentPostID()
            );
        }
        
        return $_aFormatted + $aInput;
        
    }
    
}