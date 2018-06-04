<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */


/**
 * Deals with the plugin admin pages.
 * 
 * @since        1
 */
class Externals_ExternalType_RSSView_feed extends Externals_AdminPageFramework {
        
    /**
     * Sets up admin pages.
     */
    public function __construct() {
        
        // Do render the RSS view.
        add_action(
            'init',
            array( $this, 'replyToOutput' )
        );
        
        // Post type post listing table action links
        if ( is_admin() ) {            
            add_action(
                Externals_Registry::HOOK_SLUG . '_filter_action_links_' . Externals_Registry::$aPostTypes[ 'external' ] . '_' . 'feed',
                array( $this, 'replyToSetActionLink' ),
                10,
                2
            );
            
            add_filter(
                'style_Externals_PostType',
                array( $this, 'replyToAddCustomStyle' )
            );
        }
        
    }
        
    /**
     * 
     * @callback        action      externals_action_after_loading_plugin
     */
    public function replyToOutput() {
        
        if ( ! $this->_shouldProceed() ) {
            return;
        }
        
        $_aArguments = $_GET;
        $_aArguments[ 'template_path' ] = Externals_Registry::$sDirPath . '/template/rss2/template.php';       

        header( 
            'Content-Type: ' . feed_content_type( 'rss-http' ) . '; '
                . 'charset=' . get_option( 'blog_charset' ),
            true 
        );

        getExternals(
            $_aArguments,
            true    // echo or return 
        );        
        exit;
        
    }
        /**
         * @return      boolean
         */
        private function _shouldProceed() {
            if ( ! isset( $_GET[ 'output' ], $_GET[ 'externals' ] ) ) {
                return false;
            }
                
            if ( 'rss2' !== $_GET[ 'output' ] ) {
                return false;
            }
            if ( 'externals' !== $_GET[ 'externals' ] ) {
                return false;
            }
            return true;
        }    
        
        
    /**
     * @return      array
     */
    public function replyToSetActionLink( $aActionLinks, $oPost ) {
        
        $_sURLRSSView = add_query_arg(
            array(
                'output'    => 'rss2',
                'id'        => $oPost->ID,
                'externals' => Externals_Registry::$aPostTypes[ 'external' ],
            ),
            site_url()
        );
        $_sURLRSSView = esc_url( $_sURLRSSView );
        $_sIconURL    = esc_url( Externals_Registry::getPluginURL( 'asset/image/rss16x16.gif' ) );
        $aActionLinks[ 'rss' ] = "<a href='{$_sURLRSSView}' target='_blank'>"
                . "<img src='{$_sIconURL}'/>"
            . "</a>";
        
        return $aActionLinks;
        
    }
    
    /**
     * @return      string
     */
    public function replyToAddCustomStyle( $sCSSRules ) {
        
        return $sCSSRules . PHP_EOL
. " span.rss a img {
    margin: 0;
    padding: 0;
    vertical-align: bottom;
}
" . PHP_EOL;
    }
        
}