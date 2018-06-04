<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box,
 */
class Externals_MetaBox_ItemLookupExternal_Main extends Externals_PostMetaBox_Base {
    
    /**
     * Stores the unit type slug(s). 
     */    
    protected $aExternalTypes = array( 
        'item_lookup',
    );
    
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
        
        $_oFields = new Externals_FormFields_ItemLookupExternal_Main;
        foreach( $_oFields->get() as $_aField ) {
            if ( 'external_title' === $_aField[ 'field_id' ] ) {
                continue;
            }
            $this->addSettingFields( $_aField );
        }
                    
    }
    
    /**
     * Validates submitted form data.
     */
    public function validate( $aInput, $aOriginal, $oFactory ) {    
        
        // Formats the options
        $_oArgument = new Externals_ExternalArgument_tag(
            null,
            $aInput
        );
        $_aFormatted = $_oArgument->get();
        
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