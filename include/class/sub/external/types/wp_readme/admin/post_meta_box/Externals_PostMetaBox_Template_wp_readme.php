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
class Externals_PostMetaBox_Template_wp_readme extends Externals_PostMetaBox_Base {
       
    /**
     * Stores registered external type slugs that the meta box should appear.
     * 
     */
    public $aExternalTypes = array(
        'wp_readme',
    );       
       
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
            
        $_oArgument = new Externals_ExternalArgument_wp_readme;    
    
        $this->addSettingFields(
            array(
                'type'                  => 'select',
                'field_id'              => '_output_type',
                'title'                 => __( 'Output Type', 'externals' ),
                'label_min_width'       => '100%',
                'label'                 => array(
                    'normal'               => __( 'Normal', 'externals' ),
                    'tabs'                 => __( 'Tabs', 'externals' ),
                    'accordion'            => __( 'Accordion', 'externals' ),
                ),
                'default'              => $_oArgument->get( 'output_type' ),
            ),    
            array(
                'type'                  => 'checkbox',
                'field_id'              => '_collapsed',
                'title'                 => __( 'Collapsed', 'externals' ),
                'label'                 => __( 'Collapse the output container by default.', 'externals' ),
                'default'               => $_oArgument->get( 'collapsed' ),
            ),            
            array()            
        );
        
    }
     
    /**
     * Validates submitted form data.
     */
    public function validate( /* $_aInputs, $aOriginal, $oFactory */ ) {    
                
        // $_bVerified = true;
        // $_aErrors   = array();        
        
        $_aParams    = func_get_args() + array( null, null, null );
        $_aInputs    = $_aParams[ 0 ];
        // $_aOldInputs = $_aParams[ 1 ];
                
        // if ( ! $_bVerified ) {
            
            // $this->setFieldErrors( $_aErrors );
            // $this->setSettingNotice( 
                // __( 'There is an error in your inputs.', 'externals' )
            // );
            // return $_aOldInputs;            
            
        // }

        return $_aInputs;
        
    } 
    
}