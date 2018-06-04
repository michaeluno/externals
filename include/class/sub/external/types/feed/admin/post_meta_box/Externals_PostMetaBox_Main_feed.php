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
class Externals_PostMetaBox_Main_feed extends Externals_PostMetaBox_Base {
       
    /**
     * Stores registered external type slugs that the meta box should appear.
     * 
     */
    public $aExternalTypes = array(
        'feed',
    );       
       
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
            
        $_oArgument = new Externals_ExternalArgument_feed;    
    
        $this->addSettingFields(
            array(
                'type'      => 'hidden',
                'field_id'  => '_type',
                'value'     => 'feed',
                'hidden'    => true,
            ),
            array(
                'type'       => 'select',
                'field_id'   => '_sort',
                'title'      => __( 'Sort', 'externals' ),
                'label'      => array(
                    'raw'               => __( 'Raw', 'externals' ),
                    'random'            => __( 'Random', 'externals' ),
                    'title'             => __( 'Title', 'externals' ),
                    'title_descending'  => __( 'Title Descending', 'externals' ),
                    'date'              => __( 'Date', 'externals' ),
                ),
                'default'    => $_oArgument->get( 'sort' ),
            ),        
            array(
                'type'       => 'select',
                'field_id'   => '_source_timezone',
                'title'      => __( 'Source Timezone', 'externals' ),
                'tip'        => __( 'Set the timezone of the source if the displayed time is not accurate.', 'externals' ),
                'default'    => $_oArgument->get( 'source_timezone' ),
                'label'      => array(
                    '-12'     => __( 'UTC-12', 'externals' ),
                    '-11.5'   => __( 'UTC-11:30', 'externals' ),
                    '-11'     => __( 'UTC-11', 'externals' ),
                    '-10.5'   => __( 'UTC-10:30', 'externals' ),
                    '-10'     => __( 'UTC-10', 'externals' ),
                    '-9.5'    => __( 'UTC-9:30', 'externals' ),
                    '-9'      => __( 'UTC-9', 'externals' ),
                    '-8.5'    => __( 'UTC-8:30', 'externals' ),
                    '-8'      => __( 'UTC-8', 'externals' ),
                    '-7.5'    => __( 'UTC-7:30', 'externals' ),
                    '-7'      => __( 'UTC-7', 'externals' ),
                    '-6.5'    => __( 'UTC-6:30', 'externals' ),
                    '-6'      => __( 'UTC-6', 'externals' ),
                    '-5.5'    => __( 'UTC-5:30', 'externals' ),
                    '-5'      => __( 'UTC-5', 'externals' ),
                    '-4.5'    => __( 'UTC-4:30', 'externals' ),
                    '-4'      => __( 'UTC-4', 'externals' ),
                    '-3.5'    => __( 'UTC-3:30', 'externals' ),
                    '-3'      => __( 'UTC-3', 'externals' ),
                    '-2.5'    => __( 'UTC-2:30', 'externals' ),
                    '-2'      => __( 'UTC-2', 'externals' ),
                    '-1.5'    => __( 'UTC-1:30', 'externals' ),
                    '-1'      => __( 'UTC-1', 'externals' ),
                    '-0.5'    => __( 'UTC-0:30', 'externals' ),
                    '0'       => __( 'UTC+0', 'externals' ),
                    '0.5'     => __( 'UTC+0:30', 'externals' ),
                    '1'       => __( 'UTC+1', 'externals' ),
                    '1.5'     => __( 'UTC+1:30', 'externals' ),
                    '2'       => __( 'UTC+2', 'externals' ),
                    '2.5'     => __( 'UTC+2:30', 'externals' ),
                    '3'       => __( 'UTC+3', 'externals' ),
                    '3.5'     => __( 'UTC+3:30', 'externals' ),
                    '4'       => __( 'UTC+4', 'externals' ),
                    '4.5'     => __( 'UTC+4:30', 'externals' ),
                    '5'       => __( 'UTC+5', 'externals' ),
                    '5.5'     => __( 'UTC+5:30', 'externals' ),
                    '5.75'    => __( 'UTC+5:45', 'externals' ),
                    '6'       => __( 'UTC+6', 'externals' ),
                    '6.5'     => __( 'UTC+6:30', 'externals' ),
                    '7'       => __( 'UTC+7', 'externals' ),
                    '7.5'     => __( 'UTC+7:30', 'externals' ),
                    '8'       => __( 'UTC+8', 'externals' ),
                    '8.5'     => __( 'UTC+8:30', 'externals' ),
                    '8.75'    => __( 'UTC+8:45', 'externals' ),
                    '9'       => __( 'UTC+9', 'externals' ),
                    '9.5'     => __( 'UTC+9:30', 'externals' ),
                    '10'      => __( 'UTC+10', 'externals' ),
                    '10.5'    => __( 'UTC+10:30', 'externals' ),
                    '11'      => __( 'UTC+11', 'externals' ),
                    '11.5'    => __( 'UTC+11:30', 'externals' ),
                    '12'      => __( 'UTC+12', 'externals' ),
                    '12.75'   => __( 'UTC+12:45', 'externals' ),
                    '13'      => __( 'UTC+13', 'externals' ),
                    '13.75'   => __( 'UTC+13:45', 'externals' ),
                    '14'      => __( 'UTC+14', 'externals' ),
                ),
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