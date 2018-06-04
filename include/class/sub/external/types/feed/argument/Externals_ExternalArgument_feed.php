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
class Externals_ExternalArgument_feed extends Externals_ExternalArgument_Base {

    /**
     * Stores the unit type.
     */
    public $sExternalType = 'feed';

    /**
     * Stores the default structure and key-values of the unit.
     * @remark      Accessed from the base class constructor to construct a default option array.
     */
    public static $aStructure_Default = array(
        'sort'              => 'date',
        'source_timezone'   => '0', // (string) as PHP does not allow a decimal number as an array index.
        'block_containing'    => array(
            'title'       => '',
            'description' => '',
            'content'     => '',
            'author'      => '',
            'permalink'   => '',
            'image'       => '',
        ),
        'block_not_containing'  => array(
            'title'       => '',
            'description' => '',
            'content'     => '',
            'author'      => '',
            'permalink'   => '',
            'image'       => '',        
        ),
        'blacklist_image'    => array(
            'src'   => '',
        ),        
    );

}