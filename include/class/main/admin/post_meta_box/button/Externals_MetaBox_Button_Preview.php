<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box that shows a button preview.
 */
class Externals_MetaBox_Button_Preview extends Externals_MetaBox_Button {

    public function setUp() {
               
        $_oFIelds = new Externals_FormFields_Button_Preview;
        $_aFields = $_oFIelds->get();
        foreach( $_aFields as $_aField ) {
            $this->addSettingFields( $_aField );
        }
        
    }   
    
}