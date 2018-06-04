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
class Externals_ExternalType_wp_readme extends Externals_ExternalType_Base {
    
    /**
     * Stores the type slug.
     */
    public $sTypeSlug = 'wp_readme';

    public function construct() {}
    
    /**
     * Returns the default template directory path for this external type.
     * @callback        filter      externals__filter_default_template_directory_path_of_{external type slug},
     * @return          string
     */
    public function getDefaultTemplateDirPath( $sDirPath ) {
        return Externals_Registry::$sDirPath 
            . DIRECTORY_SEPARATOR . 'template'
            . DIRECTORY_SEPARATOR . 'wp_readme';
    }
    
    /**
     * @return      string
     * @callback    filter      externals_filter_external_type_label
     */
    public function getLabel( $sExternalType ) {
        if ( ! in_array( $sExternalType, array( $this->sTypeSlug, '', false, 0, null ), true ) ) {
            return $sExternalType;
        }
        return __( 'Readme', 'externals' );
    }
   
    /**
     * @return      string
     * @callback    filter      externals_filter_external_output_wp_readme
     */
    public function getOutput( $sOutput, $aArguments ) {
        $_oOutput = new Externals_ExternalOutput_wp_readme( $aArguments );
        return $_oOutput->get();
    }
    
}