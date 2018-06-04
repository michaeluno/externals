<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box for the button post type.
 */
class Externals_MetaBox_Button_Background extends Externals_MetaBox_Button {

    
    public function setUp() {        
    
        $this->addSettingFields(
            array(
                'field_id'      => 'background_switch',
                'type'          => 'revealer',
                'select_type'   => 'radio',
                'title'         => __( 'Background Switch', 'externals' ),
                'label'         => array(
                    '.background_on'  => __( 'On', 'externals' ),
                    '.background_off' => __( 'Off', 'externals' ),
                ),
                'attributes'    => array(
                    '.background_on'  => array(
                        'data-switch' => '.background_off',
                    ),                
                    '.background_off' => array(
                        'data-switch' => '.background_on',
                    ), 
                ),       
                'default'       => '.background_on',
            ),        
            array(
                'field_id'      => 'background_type',
                'type'          => 'revealer',
                'select_type'   => 'radio',
                'title'         => __( 'Background Type', 'externals' ),
                'label'         => array(
                    '.gradient'  => __( 'Gradient', 'externals' ),
                    '.solid'     => __( 'Solid', 'externals' ),
                ),
                'class'         => array(
                    'fieldrow'  => 'background_on',
                ),                 
                'attributes'    => array(
                    '.gradient' => array(
                        'data-property'         => 'background-gradient',
                        'data-control-display'  => 'background-gradient',
                        'data-switch'           => '.solid',
                    ),
                    '.solid' => array(
                        'data-property'         => 'background-solid',
                        'data-control-display'  => 'background-solid',
                        'data-switch'           => '.gradient',
                    ),                    
                ),                   
                'default'       => '.gradient',
            ),
            array(
                'field_id'      => 'background',
                'type'          => 'color',
                'title'         => __( 'Background Solid Color', 'externals' ),
                'class'         => array(
                    'fieldrow' => 'solid background_on',
                ),
                'attributes'    => array(
                    'data-property' => 'background',
                ),
                'default'       => '#4997e5', // '#3498db',
            ),
            
            array(
                'field_id'      => 'bg_start_gradient',
                'type'          => 'color',
                'title'         => __( 'Gradient Start Color', 'externals' ),
                'class'         => array(
                    'fieldrow' => 'gradient background_on',
                ),            
                'attributes'    => array(
                    'data-property' => 'bg-start-gradient',
                ),
                'default'       => '#4997e5', // '#3498db',      
            ),
            array(
                'field_id'      => 'bg_end_gradient',
                'type'          => 'color',
                'title'         => __( 'Gradient End Color', 'externals' ),
                'class'         => array(
                    'fieldrow' => 'gradient background_on',
                ),      
                'attributes'    => array(
                    'data-property' => 'bg-end-gradient',
                ),                
                'default'       => '#3f89ba', // '#2980b9',
            )            
        );        
    }
    
}