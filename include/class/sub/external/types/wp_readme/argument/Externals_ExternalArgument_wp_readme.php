<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazon-auto-links/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles search unit options.
 * 
 // * @since       1

 */
class Externals_ExternalArgument_wp_readme extends Externals_ExternalArgument_Base {

    /**
     * Stores the unit type.
     */
    public $sExternalType = 'wp_readme';

    /**
     * Stores the default structure and key-values of the unit.
     * @remark      Accessed from the base class constructor to construct a default option array.
     */
    public static $aStructure_Default = array(
        'available_sections' => array(
            'Title'                       => true,
            'Description'                 => true,
            'Installation'                => true,
            'Frequently asked questions'  => true,
            'Other Notes'                 => true,
            'Screenshots'                 => true,
            'Changelog'                   => true,        
        ),
        'custom_sections' => array(
            // 'My Section',  'Tips', 'Credit' ...  etc.
        ),

        // template options
        'output_type'   => 'accordion',
        'collapsed'     => false,
        
    );

}