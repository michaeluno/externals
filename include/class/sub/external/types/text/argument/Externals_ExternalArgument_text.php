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
class Externals_ExternalArgument_text extends Externals_ExternalArgument_Base {

    /**
     * Stores the unit type.
     */
    public $sExternalType = 'text';

    /**
     * Stores the default structure and key-values of the unit.
     * @remark      Accessed from the base class constructor to construct a default option array.
     */
    public static $aStructure_Default = array(
        'sort'  => 'raw',
        'start' => 1, // change this to range e.g. 20-48, 53, 120-122
        'end'   => -1,
    );

}