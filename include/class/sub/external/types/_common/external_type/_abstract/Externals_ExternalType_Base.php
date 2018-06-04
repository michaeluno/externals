<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */


/**
 * The bootstrap class that loads a external type component.
 * 
 * @since        1
 */
abstract class Externals_ExternalType_Base extends Externals_PluginUtility {

    public $sTypeSlug = 'default';

    /**
     * Sets up hooks.
     */
    public function __construct() {
    
        add_filter( 
            Externals_Registry::HOOK_SLUG . '_filter_external_type_label',
            array( $this, 'getLabel' )
        );
        
        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_external_output_' . $this->sTypeSlug,
            array( $this, 'getOutput' ),
            10,
            2
        );
        
        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_registered_external_types',
            array( $this, 'replyToSetRegisteredTypes' )
        );
        
        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_default_template_directory_path_of_' . $this->sTypeSlug,
            array( $this, 'getDefaultTemplateDirPath' )
        );
        
        $_sAdminClass = "Externals_AdminPage_" . $this->sTypeSlug;
        if ( class_exists( $_sAdminClass ) ) {            
            new $_sAdminClass;
        }
               
        $this->construct();
        
    }
    

    /**
     * @remark      override this method in an extended class and return its own template directory path.
     * @callback    filter      externals__filter_default_template_directory_path_of_{external type slug},
     */
    public function getDefaultTemplateDirPath( $sDirPath ) {
        return $sDirPath;
    }    
    
        /**
         * @return      array
         */
        public function replyToSetRegisteredTypes( $aTypes ) {
            $aTypes[ $this->sTypeSlug ] = $this->sTypeSlug;
            return $aTypes;
        }
    
    /**
     * The user constructor. 
     * @remark      
     * @return      void
     */
    public function construct() {}
    
    /**
     * @return      string
     */
    public function getLabel( $sExternalType ) {
        return __( 'Default', 'externals' );
    }
   
    /**
     * @return      string
     */
    public function getOutput( $sOutput, $aArguments ) {
        return '';
    }
    
}