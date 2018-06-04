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
class Externals_MetaBox_Button_Border extends Externals_MetaBox_Button {

    
    public function setUp() {    

        $this->addSettingFields(
            array(
                'field_id'      => 'border_radius',
                'type'          => 'number',
                'title'         => __( 'Border Radius', 'externals' ),
                'default'       => 4,
                'attributes'    => array(
                    'min'           => 0,
                    'data-property' => 'border-radius',
                ),
            ),
            array(
                'field_id'      => 'border_style_switch',
                'type'          => 'revealer',
                'select_type'   => 'radio',
                'title'         => __( 'Border Style Switch', 'externals' ),
                'label'         => array(
                    '.border_style_on' => __( 'On', 'externals' ),
                    '.border_style_off' => __( 'Off', 'externals' ),
                ),
                'attributes'    => array(
                    '.border_style_on' => array(
                        'data-switch' => '.border_style_off',
                    ),                
                    '.border_style_off' => array(
                        'data-switch' => '.border_style_on',
                    ), 
                ),                                          
                'default'       => '.border_style_off',
            ),
            // Revealer items
            array(
                'field_id'      => 'border_color',
                'type'          => 'color',
                'title'         => __( 'Border Color', 'externals' ),
                'class'         => array(
                    'fieldrow'  => 'border_style_on',
                ),              
                'attributes'    => array(
                    'data-property' => 'border-color',
                ),                
                'default'       => '#1f628d',
            ),
            array(
                'field_id'      => 'border_style',
                'type'          => 'select',
                'title'         => __( 'Border Style', 'externals' ),
                'label'         => array(
                    'solid'  => __( 'Solid', 'externals' ),
                    'dotted' => __( 'Dotted', 'externals' ),
                ),
                'attributes'    => array(
                    'select'    => array(
                        'data-property' => 'border-style',
                    )
                ),                  
                'class'         => array(
                    'fieldrow'  => 'border_style_on',
                ),
            ),
            array(
                'field_id'      => 'border_width',
                'type'          => 'number',
                'title'         => __( 'Border Width', 'externals' ),
                'attributes'    => array(
                    'data-property' => 'border-width',
                ),                       
                'class'         => array(
                    'fieldrow'  => 'border_style_on',
                ),       
                'default'       => 1,
            )
        );    
    
    }
    
    
}