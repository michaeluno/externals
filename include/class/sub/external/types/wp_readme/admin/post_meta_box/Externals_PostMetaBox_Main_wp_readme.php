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
class Externals_PostMetaBox_Main_wp_readme extends Externals_PostMetaBox_Base {
       
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
                'type'      => 'hidden',
                'field_id'  => '_type',
                'value'     => 'wp_readme',
                'hidden'    => true,
            ),         
            array(
                'type'                  => 'checkbox',
                'field_id'              => '_available_sections',
                'title'                 => __( 'Available Sections', 'externals' ),
                'label_min_width'       => '100%',
                'select_all_button'     => true,
                'select_none_button'    => true,
                'label'                 => array(
                    'Title'                       => __( 'Title', 'externals' ),
                    'Description'                 => __( 'Description', 'externals' ),
                    'Installation'                => __( 'Installation', 'externals' ),
                    'Frequently asked questions'  => __( 'Frequently asked questions ', 'externals' ),
                    'Other Notes'                 => __( 'Other Notes', 'externals' ),
                    'Screenshots'                 => __( 'Screen-shots', 'externals' ),
                    'Changelog'                   => __( 'Change Log', 'externals' ),
                ),
                'default'              => $_oArgument->get( 'available_sections' ),
            ),   
            array(
                'type'            => 'text',
                'field_id'        => '_custom_sections',
                'title'           => __( 'Custom Section Name', 'externals' ),
                'repeatable'      => true,
                'tip'             => __( 'Set custom section header names to display', 'externals' ),
                
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