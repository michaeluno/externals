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
class Externals_PostMetaBox_Cache extends Externals_PostMetaBox_Base {
       
    /**
     * Sets up form fields.
     */ 
    public function setUp() {

        $_oFields = new Externals_FormFields_External_Cache;
        $_aFields = $_oFields->get( '_' );
        foreach( $_aFields as $_aField ) {           
            $this->addSettingFields( $_aField );
        }

    }
    
    /**
     * Validates submitted form data.
     */
    public function validate( /* $aInputs, $aOriginal, $oFactory */ ) {    
        
        $_aParams = func_get_args() + array( null, null, null );
        $_aInputs = $_aParams[ 0 ];
        return $_aInputs;
        
    } 
    
}