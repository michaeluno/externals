<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides shared methods for the category select form.
 * 
 */
abstract class Externals_Form_CategorySelect_Base extends Externals_PluginUtility {

    /**
     * Sets up basic properties.
     */
    public function __construct() {
        
        $this->sCharEncoding = get_bloginfo( 'charset' ); 
        $this->oEncrypt      = new Externals_Encrypt;
        $this->oDOM          = new Externals_DOM;        
        
        $_aParams = func_get_args();
        call_user_func_array(
            array( $this, 'construct' ),
            $_aParams
        );
    }
    
    /**
     * User constructor.
     */
    public function construct() {}
    

    /**
     * Checks whether the category item limit is reached.
     * 
     */
    protected function isNumberOfCategoryReachedLimit( $iNumberOfCategories ) {
        $_oOption = Externals_Option::getInstance();
        return ( boolean ) $_oOption->isReachedCategoryLimit( 
            $iNumberOfCategories
        );            
    }   
    
}
        