<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles actions and action links of post listing table of the 'externals' post type.
 * 
 * @package     Externals
 * @since       1
 */
class Externals_ExternalPostType_ListTableActionHandler extends Externals_PluginUtility {
    
    public $sCustomNoncePrefix;
    public $sCustomNonce;
    
    public $oFactory;
    
    /**
     * Sets up properties.
     */
    public function __construct( $oFactory ) {
        
        if ( $this->hasBeenCalled( __METHOD__ ) ) {
            return;
        }
        
        $this->oFactory = $oFactory;
        
        $this->sCustomNoncePrefix = Externals_Registry::TRANSIENT_PREFIX . '_Nonce_';
        
        $this->sCustomNonce = uniqid();  
        
        new Externals_ExternalPostType_ListTableAction_clear_cache(
            $this->sCustomNonce,
            $oFactory
        );
        
        $this->_handleCustomActions();   
        
        $this->setTransient( 
            $this->sCustomNoncePrefix . $this->sCustomNonce, 
            $this->sCustomNonce, 
            60*60   // 1 hour lifespan
        );        
        
    }
    
    
    /**
     * @since       1
     */
    private function _handleCustomActions() {
        
        if ( ! $this->_shouldProceed() ) {
            return;
        }
        
        $_sNonce = $this->getTransient( $this->sCustomNoncePrefix . $_GET[ 'nonce' ] );
        if ( false === $_sNonce ) { 
            new Externals_AdminPageFramework_AdminNotice(
                __( 'The action could not be processed due to the inactivity.', 'externals' ),
                array(
                    'class' => 'error',
                )
            );
            return;
        }
        $this->deleteTransient( $this->sCustomNoncePrefix . $_GET[ 'nonce' ] );

        do_action(
            Externals_Registry::HOOK_SLUG . '_action_external_post_type_listing_table_action_' . $_GET[ 'custom_action' ],
            $this->getAsArray( $_GET[ 'post' ] ),
            $this->oFactory
        );
        
        // $_sClassName = "Externals_ExternalPostType_ListTableAction_{$_GET[ 'custom_action' ]}";
        // if ( class_exists( $_sClassName ) ) {
            // new $_sClassName( $this->getAsArray( $_GET[ 'post' ] ), $this->oFactory );
        // }
        
        // Reload the page without query arguments so that the admin notice will not be shown in the next page load with other actions.
        $_sURLSendback = add_query_arg(
            array(
                'post_type' => Externals_Registry::$aPostTypes[ 'external' ],
            ),
            admin_url( 'edit.php' )
        );
        wp_safe_redirect( $_sURLSendback );    
        exit();
    
    }    

        /**
         * @return      boolean
         */
        private function _shouldProceed() {
            if ( ! isset( $_GET[ 'custom_action' ], $_GET[ 'nonce' ], $_GET[ 'post' ], $_GET[ 'post_type' ] ) ) { 
                return false;
            }
            // If a WordPress action is performed, do nothing.
            if ( isset( $_GET[ 'action' ] ) ) {
                return false;
            }
            return true;
        }
    

}