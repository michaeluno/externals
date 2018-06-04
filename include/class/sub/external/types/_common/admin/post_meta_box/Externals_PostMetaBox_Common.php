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
class Externals_PostMetaBox_Common extends Externals_PostMetaBox_Base {
       
    /**
     * Sets up form fields.
     */ 
    public function setUp() {

        $_oArgument = new Externals_ExternalArgument_default;
    
        $this->addSettingFields(
            array(
                'type'       => 'textarea',
                'field_id'   => '_url',
                'title'      => __( 'URLs', 'externals' ),
                'tip'        => __( 'For multiple URLs, enter one per line.', 'externals' ),
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),
            ),
            array(
                'type'       => 'number',
                'field_id'   => '_count',
                'title'      => __( 'Number of Items', 'externals' ),
                'tip'        => array(
                    __( 'Set the maximum number of items to display.', 'externals' ),
                    __( 'Set <code>-1</code> for no limit.', 'externals' ),
                ),
                'attributes'    => array(
                    'min'   => -1,
                    'step'  => 1,
                ),
                'default'    => $_oArgument->get( 'count' ),
            ),         
            // @todo Examine whether this should belong to template options.
            // array(
                // 'type'       => 'checkbox',
                // 'field_id'   => '_show_last_modified_date',
                // 'title'      => __( 'Last Modified Date', 'externals' ),
                // 'label'      => __( 'Insert the last modified date of the item.', 'externals' ),
                // 'default'    => $_oArgument->get( 'show_last_modified_date' ),
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
        
        // URL
        $_aInputs[ '_url' ] = $this->_getURLsSanitized( $_aInputs[ '_url' ] );
        
        if ( ! $_bVerified ) {
            
            $this->setFieldErrors( $_aErrors );
            $this->setSettingNotice( 
                __( 'There is an error in your inputs.', 'externals' )
            );
            return $_aOldInputs;            
            
        }
 
        return $_aInputs;
        
    } 
        /**
         * @return      string
         */
        private function _getURLsSanitized( $sURLs ) {
            
            $_aURLs = $this->oUtil->getExploded( $sURLs, PHP_EOL );
            return implode( PHP_EOL, $_aURLs );
            
            /* 
            foreach( $_aURLs as $_iIndex => $_sURL ) {
                $_sURL = trim( $_sURL );
                if ( false === filter_var( $_sURL, FILTER_VALIDATE_URL ) ) {
                    $_aErrors[ '_url' ] = __( 'Enter a url.', 'externals' );
                    $_bVerified = false;
                }     
                $_aURLs[ $_iIndex ] = $_sURL;
            }
            $_aInputs[ '_url' ] = implode( PHP_EOL, $_aURLs ); */
        
        }
    
}