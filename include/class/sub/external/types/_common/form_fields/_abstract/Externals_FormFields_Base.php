<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides abstract methods for form fields.
 * 
 * @since       1  
 */
abstract class Externals_FormFields_Base extends Externals_PluginUtility {

    /**
     * Stores the option object.
     */
    public $oOption;
    
    public $oTemplateOption;
    
    public function __construct() {
        
        $this->oOption         = Externals_Option::getInstance();
        $this->oTemplateOption = Externals_TemplateOption::getInstance();
        
    }
    
    /**
     * Should be overridden in an extended class.
     * 
     * @remark      Do not even declare this method as parameters will be vary 
     * and if they are different PHP will throw errors.
     */
    // public function get() {}
  
}