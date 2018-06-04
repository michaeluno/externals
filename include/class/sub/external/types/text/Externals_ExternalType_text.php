<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */


/**
 * The bootstrap class that loads a external type component.
 * 
 * @since        1
 */
class Externals_ExternalType_text extends Externals_ExternalType_Base {

    /**
     * Stores the type slug.
     */
    public $sTypeSlug = 'text';
    
    /**
     * @return      string
     * @callback    filter      externals_filter_external_type_label
     */
    public function getLabel( $sExternalType ) {
        if ( ! in_array( $sExternalType, array( 'text', '', false, 0, null ), true ) ) {
            return $sExternalType;
        }
        return __( 'Text', 'externals' );
    }
   
    /**
     * @return      string
     * @callback    filter      externals_filter_external_output_text
     */
    public function getOutput( $sOutput, $aArguments ) {        
        $_oOutput = new Externals_ExternalOutput_text( $aArguments );
        return $_oOutput->get();
    }
    
}