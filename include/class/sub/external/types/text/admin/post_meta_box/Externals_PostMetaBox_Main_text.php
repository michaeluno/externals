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
class Externals_PostMetaBox_Main_text extends Externals_PostMetaBox_Base {
       
    /**
     * Stores registered external type slugs that the meta box should appear.
     * 
     */
    public $aExternalTypes = array(
        'text',
    );
    
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
// return;    
        $_oArgument = new Externals_ExternalArgument_text;
    
        $this->addSettingFields(
            array(
                'type'      => 'hidden',
                'field_id'  => '_type',
                'value'     => 'text',
                'hidden'    => true,
            ),
     
            array(
                'type'       => 'select',
                'field_id'   => '_sort',
                'title'      => __( 'Sort', 'externals' ),
                'label'      => array(
                    'raw'      => __( 'Raw', 'externals' ),
                    'random'   => __( 'Random', 'externals' ),
                ),
                'default'    => $_oArgument->get( 'sort' ),
            ),     
            
            // array(
                // 'type'          => 'number',
                // 'field_id'      => '_lines',
                // 'title'         => __( 'Lines', 'externals' ),
                // 'tip'           => array(
                    // __( 'Specify the line numbers to extract.', 'externals' )
                    // . ' ' . __( 'Do not set any to show all.', 'externals' ),
                // ),
                // 'label'         => array(
                    // 'start' => __( 'Start', 'externals' ),
                    // 'end'   => __( 'End', 'externals' ),
                // ),
                // 'attributes'    => array(
                    // 'min'   => 0,
                // ),
                // 'repeatable'    => true,
            // ),
            array()            
        );

        
    }
     
    /**
     * Validates submitted form data.
     */
    public function validate( /* $_aInputs, $aOriginal, $oFactory */ ) {    
                
        $_bVerified = true;
        $_aErrors   = array();        
        
        $_aParams    = func_get_args() + array( null, null, null );
        $_aInputs    = $_aParams[ 0 ];
        $_aOldInputs = $_aParams[ 1 ];
                
        // Line numbers
        // $_aLines = $this->oUtil->getAsArray( $_aInputs[ '_lines' ] );
        // foreach( $_aLines as $_aLine ) {
            // if ( $_aLine[ 'start' ] > $_aLine[ 'end' ] ) {
                // $_bVerified = false;
                // $_aErrors[ '_lines' ] = __( 'The start line must be smaller than the end.', 'externals' );
            // }
        // }

        if ( ! $_bVerified ) {
            
            $this->setFieldErrors( $_aErrors );
            $this->setSettingNotice( 
                __( 'There is an error in your inputs.', 'externals' )
            );
            return $_aOldInputs;            
            
        }
        
        
        return $_aInputs;
        
    } 
    
}