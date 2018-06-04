<?php
/**
 * Amazon Auto Links
 * 
 * http://en.michaeluno.jp/amazon-auto-links/
 * Copyright (c) 2013-2015 Michael Uno
 * 
 */

/**
 * Provides methods to clear caches of an external.
 * 
 * @package     Externals
 * @since       1
 */
class Externals_ExternalPostType_ListTableAction_clear_cache extends Externals_PluginUtility {

    public $sActionName = 'clear_cache';

    public $sNonce;
    public $oFactory;

    /**
     * Sets up hooks.
     * @since       1
     */
    public function __construct( $sNonce, $oFactory ) {
        
        $this->sNonce   = $sNonce;
        $this->oFactory = $oFactory;
        
        add_action(
            Externals_Registry::HOOK_SLUG . '_action_external_post_type_listing_table_action_' . $this->sActionName,
            array( $this, 'replyToDoAction' ),
            10,
            2
        );
        
        add_filter(
            'action_links_' . Externals_Registry::$aPostTypes[ 'external' ],
            array( $this, 'replyToModifyActionLinks' ),
            10,
            2
        );        
        
    }
        /**
         * @return      array
         * @since       1
         */
        public function replyToModifyActionLinks( $aActionLinks, $oPost ) {
            
            // Front end url to view the raw output of the external item.
            $_sActionURL = esc_url(
                add_query_arg(                        
                    array( 
                        'post_type'     => Externals_Registry::$aPostTypes[ 'external' ],
                        'custom_action' => $this->sActionName,
                        'post'          => $oPost->ID,
                        'nonce'         => $this->sNonce,
                    ), 
                    admin_url( $this->oFactory->oProp->sPageNow )                     
                )
            );
            $aActionLinks[ $this->sActionName ] = "<a href='{$_sActionURL}'>"
                    . __( 'Clear Cache', 'externals' )
                . "</a>";             
            return $aActionLinks;
            
        }
        
        /**
         * Perform the action
         */
        public function replyToDoAction( $aPostIDs, $oFactory ) {
         
            // Check the limitation.
            // $_oOption         = Externals_Option::getInstance();

            
            $_iFailed       = 0;
            $_iTotalCleared = 0;
            foreach( $aPostIDs as $_iPostID ) {
                $_iClearedItems = $this->_clearCache( $_iPostID );                
                if ( $_iClearedItems ) {
                    $_iTotalCleared = $_iTotalCleared + $_iClearedItems;
                } else {
                    $_iFailed++;
                }
                
            }
            
            if ( $_iClearedItems ) {               
                $oFactory->setSettingNotice(
                    sprintf( 
                        __( '%1$s cache item(s) have been cleared.', 'externals' ),
                        $_iClearedItems
                    ),
                    'updated'                
                );         
            }
            if ( $_iFailed ) {                
                $oFactory->setSettingNotice(
                    sprintf( 
                        __( '%1$s cache item(s) could not be cleared.', 'externals' ),
                        $_iFailed
                    ),
                    'error'                
                );         
            }
         
        }
            /**
             * Clears cache of the given external id.
             * 
             * @since       1
             * @return      boolean
             */
            private function _clearCache( $iPostID ) {
                
                $_sURLs         = get_post_meta( $iPostID, '_url', true ); 
                if ( empty( $_sURLs ) ) {
                    return false;
                }
                // $_aURLs = explode( PHP_EOL, $_sURLs ); misses some cases
                $_aURLs = preg_split( "/[\n\r]+/", $_sURLs );
                
                $_oHTTP = new Externals_HTTPClient( $_aURLs );           
                $_oHTTP->deleteCache();
// @todo Get the exact result (count) of deleted items.                
                return count( $_aURLs );
                
            }           
            
}