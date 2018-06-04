<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box added to the externals definition page.
 */
abstract class Externals_PostMetaBox_Base extends Externals_AdminPageFramework_MetaBox {
    
    /**
     * Stores registered external type slugs that the meta box should appear.
     * 
     * Each extended class should override this value.
     * 
     * @remark      will be set in the user constructor if not set.
     */
    public $aExternalTypes;
    
    /**
     * User constructor.
     */
    public function start() {
        
        $this->oUtil = new Externals_PluginUtility;
        
        if ( ! isset( $this->aExternalTypes ) ) {            
            $this->aExternalTypes = $this->_getRegisteredExternalTypes();
        }
    }
        private function _getRegisteredExternalTypes() {
            if ( isset( self::$_aRegisteredExternalTypes ) ) {
                return self::$_aRegisteredExternalTypes;
            }
            self::$_aRegisteredExternalTypes = apply_filters(
                Externals_Registry::HOOK_SLUG . '_filter_registered_external_types',
                array()
            );
            return self::$_aRegisteredExternalTypes;
        }
            static private $_aRegisteredExternalTypes;
    
    /**
     * Checks whether the meta box should be registered or not in the loading page.
     */
    public function _isInThePage() {

        if ( ! parent::_isInThePage() ) {
            return false;
        }
        
        // At this point, it is TRUE evaluated by the framework.
        // but we need to evaluate it for the plugin.
                
        // Post post saving page (invisible to the user as it gets redirected by the system)
        if ( isset( $_POST[ 'post_type' ], $_POST[ '_type' ] ) ) {
            
            if ( Externals_Registry::$aPostTypes[ 'external' ] !== $_POST[ 'post_type' ] ) {
                return false;
            }
            
            return in_array(
                $_POST[ '_type' ],
                $this->aExternalTypes,
                true
            );

        }        
    
        return in_array(
            $this->_getExternalType(),
            $this->aExternalTypes,
            true
        );        
        
    }
        /**
         * @access      protected       The template post meta box class acecss it.
         * @return      string
         * @since       1
         */
        protected function _getExternalType() {
            
            // Get the post ID.
            $iPostID = $this->oUtil->getCurrentPostID();        
            if ( isset( self::$_aExternalTypeCaches[ $iPostID ] ) ) {
                return self::$_aExternalTypeCaches[ $iPostID ];
            }
        
            if ( isset( $_GET[ '_type' ] ) ) {
                return $_GET[ '_type' ];
            }
                    
            $_sExternalType = get_post_meta(
                $iPostID,      
                '_type', // meta key
                true
            );            
            
            self::$_aExternalTypeCaches[ $iPostID ] = $_sExternalType
                ? $_sExternalType
                : 'text';
                        
            return self::$_aExternalTypeCaches[ $iPostID ];
            
        }
            static private $_aExternalTypeCaches = array();
 
}