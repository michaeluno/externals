<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/amazn-auto-links/
 * Copyright (c) 2013-2015 Michael Uno
 * 
 */

/**
 * Handles unit options.
 * 
 * @since       3
 * @remark      Do not make it abstract as form fields classes need to access the default struture of the item format array.
 */
class Externals_ExternalArgument_Base extends Externals_WPUtility {

    /**
     * Stores the unit type.
     * @remark      Should be overridden in an extended class.
     */
    public $sExternalType = 'default';

    /**
     * Stores the unit ID.
     */
    public $iExternalID;

    /**
     * Stores the required common unit option keys.
     * 
     * @remark      The keys listed here do not have a prefix of underscore `_`.
     */
    public $aCommonKeys = array(
    
        /**
         * Used for taxonomy queries.
         */
        'operator'                  => 'AND',
    
        /**
         * Taxonomy terms
         */
        'label'                     => array(
        ),
        
        /**
         * External ID (post ID)
         */
        'id'                        => null,
        
        /**
         * External Type
         */
        'type'                      => null,
        
        
        'cache_duration'            => array(
            'size' => 1,
            'unit' => 86400,
        ),
        
        // Basic
        'url'                       => null,
        'count'                     => -1,        
        
    // @todo Examine whether this should belong to template options.
        // 'show_last_modified_date'   => true,
        
        // Advanced
        'timeout'                   => 5,
        'sslverify'                 => null,    // (boolean) WordPress 3.7 or above, it should be true.
        'redirection'               => 5,
        
        
        'template'                  => '',      // the template name - if multiple templates with a same name are registered, the first found item will be used.
        'template_id'               => null,    // the template ID: md5( dir path )
        'template_path'             => '',      // the template can be specified by the template path. If this is set, the 'template' key won't take effect.

        //'width'         => null,
        //'width_unit'    => '%',
        //'height'        => null,
        //'height_unit'   => 'px',
        
        //'show_errors'   => true,    // whether to show an error message.
        
    );
    
    /**
     * Stores the default option structure.
     * 
     * This one will be merged with several other key structure and $aDefault will be constructed.
     */
    static public $aStructure_Default = array();    
    
    /**
     * Stores the default unit option values and represents the array structure.
     * 
     * @remark      Should be defined in an extended class.
     */
    public $aDefault = array();
    
    /**
     * Stores the associated options to the unit.
     */
    public $aExternalOptions = array();
        
    /**
     * Sets up properties.
     * 
     * @param       integer     $iExternalID        The unit ID as a post ID.
     * @param       array       $aExternalOptions   (optional) The unit option to set. Used to sanitize unit options.
     */
    public function __construct( $iExternalID=0, array $aExternalOptions=array() ) {
        
        $this->iExternalID      = ( integer ) $iExternalID;
        $this->aDefault     = array(
                'type' => $this->sExternalType,
                'id'   => null,    // required when parsed in the Output class
            )
            + $this->getDefaultOptionStructure()
            + $this->aCommonKeys;
        $this->aExternalOptions = $iExternalID
            ? $aExternalOptions 
                + array( 'id' => $iExternalID ) 
                + $this->getPostMeta( 
                    $iExternalID,
                    null,   // key - do not set here
                    '_'    // remove an underscore prefix
                )
            : $aExternalOptions;
        $this->aExternalOptions = $this->getFormatted( $this->aExternalOptions );

    }
        /**
         * @return      array
         */
        protected function getDefaultOptionStructure() {

            // This lets PHP 5.2 access static properties of an extended class.
            $_aProperties = get_class_vars( get_class( $this ) );
            return $_aProperties[ 'aStructure_Default' ];
            
        }
    /**
     * 
     * @since       1
     * @return      array
     */
    protected function getFormatted( array $aExternalOptions ) {

        $_oOption     = Externals_Option::getInstance();        
        $aExternalOptions = $aExternalOptions + $this->aDefault;

        // Drop undefined keys.
        foreach( $aExternalOptions as $_sKey => $_mValue ) {
            if ( array_key_exists( $_sKey, $this->aDefault ) ) {
                continue;
            }
            unset( $aExternalOptions[ $_sKey ] );
        }
        
        $aExternalOptions[ 'url' ] = $this->_getFormatted_url( $aExternalOptions[ 'url' ] );        
        
        return $aExternalOptions;
        
    }    
        
        /**
         * @return      array
         * @since       1
         */
        private function _getFormatted_url( $asURLs ) {
            if ( is_array( $asURLs ) ) {
                return $asURLs;
            }
            $_sURL = str_replace( PHP_EOL, ',', $asURLs );
            return explode( ',', $_sURL );        
            
        }
  
    
    /**
     * Returns the all associated options if no key is set; otherwise, the value of the specified key.
     * 
     * @since       1
     * @return      
     */
    public function get( /* $sKey1, $sKey2, $sKey3, ... OR $aKeys, $vDefault */ ) {
    
        $_mDefault  = null;
        $_aKeys     = func_get_args() + array( null );

        // If no key is specified, return the entire option array.
        if ( ! isset( $_aKeys[ 0 ] ) ) {
            return $this->aExternalOptions;
        }
        
        // If the first key is an array, the second parameter is the default value.
        if ( is_array( $_aKeys[ 0 ] ) ) {
            $_mDefault = isset( $_aKeys[ 1 ] )
                ? $_aKeys[ 1 ]
                : null;
            $_aKeys    = $_aKeys[ 0 ];
        }    
    
        // Now either the section ID or field ID is given. 
        return $this->getArrayValueByArrayKeys( 
            $this->aExternalOptions, 
            $_aKeys,
            $_mDefault
        );    
  
    }    
    
    /**
     * Sets the options.
     */
    public function set( /* $asKeys, $mValue */ ) {
        
        $_aParameters   = func_get_args();
        if ( ! isset( $_aParameters[ 0 ], $_aParameters[ 1 ] ) ) {
            return;
        }
        $_asKeys        = $_aParameters[ 0 ];
        $_mValue        = $_aParameters[ 1 ];
        
        // string, integer, float, boolean
        if ( ! is_array( $_asKeys ) ) {
            $this->aExternalOptions[ $_asKeys ] = $_mValue;
            return;
        }
        
        // the keys are passed as an array
        $this->setMultiDimensionalArray( 
            $this->aExternalOptions, 
            $_asKeys,
            $_mValue 
        );

    }    
    
    /**
     * @return      integer     Cache duration in seconds.
     */
    public function getCacheDuration() {
        $_iSize = $this->get( array( 'cache_duration', 'size' ), 1 );
        $_iUnit = $this->get( array( 'cache_duration', 'unit' ), 60*60*24 );
        return $_iSize * $_iUnit;
    }
    
}