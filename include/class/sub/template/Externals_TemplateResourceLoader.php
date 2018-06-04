<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

 
/**
 * Loads template components such as style.css, template.php, functions.php etc.
 *  
 * @package     Externals
 * @since       1
 * 
 * @filter      apply       externals_filter_template_custom_css
 */
class Externals_TemplateResourceLoader extends Externals_PluginUtility {

    /**
     * Indicates whether the class is already loaded or not.
     * 
     */
    static private $_bLoaded = false;
  
    /**
     * Stores the template option object.
     */
    public $_oTemplateOption;
  
    public function __construct() {
        
        if ( self::$_bLoaded ) {
            return;
        }
        self::$_bLoaded = true;        
        
        $this->_oTemplateOption = Externals_TemplateOption::getInstance();
        
        $this->_loadFunctionsOfActiveTemplates();
        $this->_loadStylesOfActiveTemplates();        
        $this->_loadSettingsOfActiveTemplates();             
        
    }

    /**
     * Includes activated templates' `functions.php` files.
     * @since       1
     */    
    private function _loadFunctionsOfActiveTemplates() {    
        foreach( $this->_oTemplateOption->getActiveTemplates() as $_aTemplate ) {
            $this->includeOnce(
                ABSPATH . ltrim( $_aTemplate[ 'relative_dir_path' ], './' ) . DIRECTORY_SEPARATOR . 'functions.php'
            );
        }    
    }
    
    /**
     * 
     * @since       1
     */
    private function _loadStylesOfActiveTemplates() {
        add_action( 
            'wp_enqueue_scripts', 
            array( $this, '_replyToEnqueueActiveTemplateStyles' ) 
        );    
        // @todo Examine whether the ` wp_add_inline_style()` function can be used.
        add_action(
            'wp_enqueue_scripts',
            array( $this, '_replyToPrintActiveTemplateCustomCSSRules' )
        );
    }
        /**
         * Enqueues activated templates' CSS file.
         * 
         * @callback        action      wp_enqueue_scripts
         */
        public function _replyToEnqueueActiveTemplateStyles() {
            
            // This must be called after the option object has been established.
            foreach( $this->_oTemplateOption->getActiveTemplates() as $_aTemplate ) {
                
                $_sCSSPath = ABSPATH . ltrim( $_aTemplate[ 'relative_dir_path' ], './' ) . DIRECTORY_SEPARATOR . 'style.css';
                $_sCSSURL  = $this->getSRCFromPath( $_sCSSPath );
                wp_register_style( "externals-{$_aTemplate[ 'id' ]}", $_sCSSURL );
                wp_enqueue_style( "externals-{$_aTemplate[ 'id' ]}" );        
                
            }
            
        }   
        /**
         * Prints a style tag by joining all the custom CSS rules set in the active template options.
         * 
         * @since       1
         * @return      void
         */
        public function _replyToPrintActiveTemplateCustomCSSRules() {
            
            $_aCSSRUles = array();
            
            // Retrieve 'custom_css' option value from all the active templates.
// @todo Add 'custom_css' field to all the template options.
            foreach( $this->_oTemplateOption->getActiveTemplates() as $_aTemplate ) {   
                $_aCSSRUles[] = $this->getElement(
                    $_aTemplate,
                    'custom_css',
                    ''
                );
            }
                        
            $_sCSSRules = apply_filters(
                'externals_filter_template_custom_css',
                trim( implode( PHP_EOL, array_filter( $_aCSSRUles ) ) )
            );
            if ( $_sCSSRules ) {
                echo "<style type='text/css' id='externals-template-custom-css'>"
                        . $_sCSSRules
                    . "</style>";
            }
            
        }
        
    /**
     * Stores loaded file paths so that PHP errors of including the same file multiple times can be avoided.
     */
    static public $_aLoadedFiles = array();
    
    /**
     * Includes activated templates' settings.php files.
     * @since       1
     */    
    private function _loadSettingsOfActiveTemplates() {
        if ( ! is_admin() ) {
            return;
        }
        foreach( $this->_oTemplateOption->getActiveTemplates() as $_aTemplate ) {
            $this->includeOnce( ABSPATH . ltrim( $_aTemplate[ 'relative_dir_path' ], './' ) . DIRECTORY_SEPARATOR . 'settings.php' );
        }        
    }    
        

  
}