<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * A base class for unit classes, search, tag, and category.
 * 
 * Provides shared methods and properties for those classes.
 * 
 * @filter          externals_filter_template_path
 *  parameter 1:    (string) template path
 *  parameter 2:    (array) arguments(unit options) 
 * 
 */
abstract class Externals_ExternalOutput_Base extends Externals_PluginUtility {

    /**
     * Stores the unit type.
     * @remark      The constructor will create a unit option object based on this value.
     */
    public $sExternalType = '';
    
    /**
     * Stores a plugin option object.
     */ 
    public $oOption;
    
    /**
     * Stores DOM parser object.
     */
    // public $oDOM;  
    
    /**
     * Stores an encoder and decoder object.
     */
    // public $oEncrypt;
    
    /**
     * The site character set.
     */
    // public $sCharEncoding ='';
              
    public $oArgument;
              
    /**
     * Sets up properties and hooks.
     */
    public function __construct( $aArguments, $sExternalType='' ) {

        $this->sExternalType            = $sExternalType
            ? $sExternalType
            : $this->sExternalType;

        $_sExternalOptionClassName      = "Externals_ExternalArgument_{$this->sExternalType}";

        $this->oArgument                = new $_sExternalOptionClassName(
            $this->getElement( $aArguments, 'id' ),
            $aArguments
        );

        $this->oOption                 = Externals_Option::getInstance();
         
        // $this->sCharEncoding        = get_bloginfo( 'charset' );
        // $this->bIsSSL                  = is_ssl();        
        $this->oDOM                    = new Externals_DOM;
        // $this->oEncrypt             = new Externals_Encrypt;
        
        $this->construct();
        
    }   
 
    /**
     * User constructor.
     */
    public function construct() {}

    /**
     * Sets up properties.
     * @remark      Should be overridden in an extended class.
     * @return      void
     */
    protected function _setProperties() {}
  
    /**
     * Finds the template path from the given arguments(unit options).
     * 
     * The keys that can determine the template path are template, template_id, template_path.
     * 
     * The template_id key is automatically assigned when creating a unit. If the template_path is explicitly set and the file exists, it will be used.
     * 
     * The template key is a user friendly one and it should point to the name of the template. If multiple names exist, the first item will be used.
     * 
     */
    protected function getTemplatePath( $aArguments ) {

        // If it is set in a request, use it.
        if ( isset( $aArguments[ 'template_path' ] ) && file_exists( $aArguments[ 'template_path' ] ) ) {
            return $aArguments[ 'template_path' ];
        }

        $_oTemplateOption = Externals_TemplateOption::getInstance();
        
        // If a template name is given in a request
        if ( isset( $aArguments[ 'template' ] ) && $aArguments[ 'template' ] ) {
            foreach( $_oTemplateOption->getActiveTemplates() as $_aTemplate ) {
                if ( strtolower( $_aTemplate[ 'name' ] ) == strtolower( trim( $aArguments[ 'template' ] ) ) ) {
                    return ABSPATH . $_aTemplate[ 'relative_dir_path' ] . '/template.php';
                }
            }
        }
                    
        // If a template ID is given,
        if ( isset( $aArguments[ 'template_id' ] ) && $aArguments[ 'template_id' ] ) {
            foreach( $_oTemplateOption->getActiveTemplates() as $_sID => $_aTemplate ) {
                if ( $_sID == trim( $aArguments[ 'template_id' ] ) ) {
                    return ABSPATH . $_aTemplate[ 'relative_dir_path' ] . '/template.php';
                }
            }        
        }
        
        // Not found. In that case, use the default one.
        return apply_filters(
            Externals_Registry::HOOK_SLUG . '_filter_default_template_path',
            ''
        );

    }
        
    /**
     * Gets the output of product links by specifying a template.
     * 
     * @return      string
     */
    public function get() {
        
        $aItems        = $this->getItems( $this->oArgument->get( 'url' ) );
        if ( $this->_isError( $aItems ) && ! $this->oArgument->get( 'show_errors' ) ) {
            return '';
        }

        $sTemplatePath = apply_filters( 
            Externals_Registry::HOOK_SLUG . "_filter_template_path", 
            $this->getTemplatePath( $this->oArgument->get() ),
            $this->oArgument->get()
        );
                
        
        // Set local variables that the themplate can access to.
        $oOption       = $this->oOption;
        $aOptions      = $this->oOption->aOptions; 
        $oArgument     = $this->oArgument;
        $aArguments    = $this->oArgument->get();
        
        // Capture the output buffer
        ob_start(); 
                
        if ( file_exists( $sTemplatePath ) ) {
            include( $sTemplatePath ); 
        } else {
            echo '<p>' 
                . Externals_Registry::NAME 
                . ': ' . __( 'the template could not be found. Try reselecting the template in the unit option page.', 'externals' )
            . '</p>';
        }
            
        $_sContent = ob_get_contents(); 
        ob_end_clean(); 
        return $_sContent;
        
    }      
        /**
         * Checks whether response has an error.
         * @return      boolean
         * @since       1.0
         */
        protected function _isError( $aItems ) {
            return empty( $aItems );
        }
      
        
    /**
     * Renders the product links.
     * 
     * @return      void
     */
    public function render( $aURLs=array() ) {
        echo $this->get( $aURLs );
    }

    /**
     * Retrieves product link data from a remote server.
     * @remark      should be extended and must return an array.
     * @return      array
     */
    public function getItems( $aURLs=array() ) {

        $_oHTTP = new Externals_HTTPClient( 
            $aURLs,
            $this->oArgument->getCacheDuration(),
            array(
                'sslverify'     => ( boolean ) $this->oArgument->get( 'sslverify' ),
                'redirection'   => ( integer ) $this->oArgument->get( 'redirection' ),
                'timeout'       => ( integer ) $this->oArgument->get( 'timeout' ),
                
                // class specific argument 
                'raw'           => true, // return the raw response rather than the body.
            )
        );
        $_aResponses = $_oHTTP->get();  
        return $this->_getItemsFormatted( $_aResponses );
        
    }

        /**
         * @return      array
         * @since       1
         */
        private function _getItemsFormatted( $_aResponses ) {
            
            $_aItems = array();
            foreach( $_aResponses as $_isIndex => $_aoResponse ) {
                
                if ( is_wp_error( $_aoResponse ) ) {
                    $_aItems[ $_isIndex ] = array(
                        'error' => $_aoResponse->get_error_message(),
                    );
                    continue;
                }
                
                $_aItems[ $_isIndex ] = array(
                    'content'   => wp_remote_retrieve_body( $_aoResponse ),
                ) 
                + $this->getAsArray( wp_remote_retrieve_headers( $_aoResponse ) );
                
            }
            return $_aItems;
        }    
    
}