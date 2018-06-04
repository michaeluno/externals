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
class Externals_MetaBox_Button_Text extends Externals_MetaBox_Button {

    public function setUp() {        
    
        $this->addSettingFields(
            array(
                'field_id'      => 'button_label',
                'type'          => 'text',
                'title'         => __( 'Button Label', 'externals' ),
                'default'       => __( 'Buy Now', 'externals' ),
                'attributes'    => array(
                    'data-property' => 'text',
                ),                
            ),                    
            array(
                'field_id'      => 'font_color',
                'type'          => 'color',
                'title'         => __( 'Font Color', 'externals' ),
                'default'       => '#ffffff',
                'attributes'    => array(
                    'data-property' => 'color',
                ),                
            ),
            array(
                'field_id'      => 'font_size',
                'type'          => 'number',
                'title'         => __( 'Font Size', 'externals' ),
                'attributes'    => array(
                    'min'           => 0,
                    'data-property' => 'font-size',
                ),                
                'default'       => 13,
            ),
            
            array(
                'field_id'      => 'text_shadow_switch',
                'type'          => 'revealer',
                'select_type'   => 'radio',
                'title'         => __( 'Text Shadow Switch', 'externals' ),
                'label'         => array(
                    '.text_shadow_on' => __( 'On', 'externals' ),
                    '.text_shadow_off' => __( 'Off', 'externals' ),
                ),
                'attributes'    => array(
                    '.text_shadow_on'  => array(
                        'data-switch' => '.text_shadow_off',
                    ),
                    '.text_shadow_off' => array(
                        'data-switch' => '.text_shadow_on',
                    )
                ),
                'default'       => '.text_shadow_off',
            ),       
            array(
                'field_id'      => 'text_shadow_color',
                'type'          => 'color',
                'title'         => __( 'Text Shadow', 'externals' ),
                'class'         => array(
                    'fieldrow'  => 'text_shadow_on',
                ),
                'attributes'    => array(
                    'data-property' => 'text-shadow-color',
                ),
            )
        );
                
    }
    
    
}