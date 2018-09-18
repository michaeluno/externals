<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles plugin options.
 * 
 * @since       1
 * @filter      apply       externals_filter_option_class_name
 */
class Externals_Option extends Externals_Option_Base {

    /**
     * Stores instances by option key.
     * 
     * @since       1
     */
    static public $aInstances = array(
        // key => object
    );
        
    /**
     * Stores the default values.
     */
    // public $aDefault = array();
    public $aDefault = array(
    
        'capabilities' => array(
            'setting_page_capability' => 'manage_options',
        ),  
        
        'debug' => array(
            'debug_mode' => 0,
        ),
    
        'cache'    =>    array(
            'chaching_mode'  => 'normal',
            'clear_interval' => array(   // 0.3.13
                'size'  => 7,
                'unit'  => 86400,
            ),
        ),
          

        'external_preview' => array(
            'visible_to_guests' => true,
        ),
        
        'reset_settings'    => array(
            'reset_on_uninstall'    => false,
        ),
        
    );
         
    /**
     * Returns the instance of the class.
     * 
     * This is to ensure only one instance exists.
     * 
     * @since       1
     */
    static public function getInstance( $sOptionKey='' ) {
        
        $sOptionKey = $sOptionKey 
            ? $sOptionKey
            : Externals_Registry::$aOptionKeys[ 'setting' ];
        
        if ( isset( self::$aInstances[ $sOptionKey ] ) ) {
            return self::$aInstances[ $sOptionKey ];
        }
        $_sClassName = apply_filters( 
            Externals_Registry::HOOK_SLUG . '_filter_option_class_name',
            __CLASS__ 
        );
        self::$aInstances[ $sOptionKey ] = new $_sClassName( $sOptionKey );
        return self::$aInstances[ $sOptionKey ];
        
    }         
            
    
    public function isAdvanced() {
        return false;
    }
    
    public function canExport() {
        return false;
    }
    
    /**
     * Checks whether the plugin debug mode is on.
     * @return      boolean
     * @remark      the scope is static because this method is also defined in one of the base classes.
     */
    static public function isDebugMode() {
        return ( boolean ) self::getInstance()->get( 
            'debug', 
            'debug_mode' 
        );
    }
    
    /**
     * 
     * @since       1
     * @return      boolean
     */
    public function isCustomPreviewPostTypeSet()  {
        
        $_sPreviewPostTypeSlug = $this->get( 'external_preview', 'preview_post_type_slug' );
        if ( ! $_sPreviewPostTypeSlug ) {
            return false;
        }
        return Externals_Registry::$aPostTypes[ 'external' ] !== $_sPreviewPostTypeSlug;
        
    }    
    
    /**
     * 
     * @since       1
     * @return      boolean
     */
    public function isPreviewVisible() {
        
        if ( $this->get( 'external_preview', 'visible_to_guests' ) ) {
            return true;
        }
        return ( boolean ) is_user_logged_in();
        
    }    
    
}