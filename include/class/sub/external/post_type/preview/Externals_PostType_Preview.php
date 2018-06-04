<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates Externals custom preview post type.
 * 
 * @package     Externals
 * @since       1
 */
class Externals_PostType_ExternalPreview {
    
    /**
     * 
     * @since         1
     */ 
    protected $sDefaultPreviewSlug = '';
    
    /**
     * @since         1
     */
    protected $sPreviewPostTypeSlug = '';
      
    /**
     * Sets up hooks and properties.
     * @since         1
     */
    public function __construct() {
        
        // If a custom preview post type is not set, do nothing.
        $_oOption = Externals_Option::getInstance();
        if ( ! $_oOption->isCustomPreviewPostTypeSet() ) {
            return;
        }
        
        // Properties
        $this->sDefaultPreviewSlug  = Externals_Registry::$aPostTypes[ 'external' ];
        $this->sPreviewPostTypeSlug = $_oOption->get( 
            'external_preview', 
            'preview_post_type_slug' 
        );
        
        // Hooks
        /// Post Type
        add_action( 'init', array( $this, '_replyToRegisterCustomPreviewPostType' ) );
        
        /// Modify the links
        add_filter( 'post_row_actions', array( $this, '_replyToAddViewActionLink' ), 10, 2 );
        add_filter( 'post_type_link', array( $this, '_replyToModifyPermalinks' ), 10, 3 );
        add_filter( 'post_link', array( $this, '_replyToModifyPermalinks' ), 10, 3 );
        add_filter( "previous_post_link", array( $this, '_replyToModifyPostLink' ), 10, 4 );
        add_filter( "next_post_link", array( $this, '_replyToModifyPostLink' ), 10, 4 );    
        
        add_filter( 'aal_filter_external_view_url', array( $this, '_replyToModifyViewURL' ), 10, 1 );
        
        /// Modify database queries
        add_filter( 'request', array( $this, '_replyToModifyDatabaseQuery' ) );

    }
    
    /**
     * Modifies the action link of the post listing table.
     * 
     * @callback    filter      post_row_actions
     * @return      array       The action link definition array.
     */
    public function _replyToAddViewActionLink( $aArctions, $oPost ) {
        $_sLink = $this->_replaceWithUserSetPostTypeSlug( get_permalink( $oPost->ID ) );
        $aArctions[ 'view' ] = '<a href="' 
            . $_sLink . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $oPost->post_title ) ) . '" rel="permalink">' 
                . __( 'View' ) 
            . '</a>';
        return $aArctions;
    }
      
    /**
	 * @param       string      $output     The adjacent post link.
	 * @param       string      $format     Link anchor format.
	 * @param       string      $link       Link permalink format.
	 * @param       WP_Post     $post       The adjacent post.
     * @callback    filter      previous_post_link
     * @callback    filter      next_post_link
	 */
    public function _replyToModifyPostLink( $sOutput, $format, $link, $oPost ) {
        
        if ( ! isset( $oPost->post_type ) ) {
            return $sOutput;
        }
        if ( $this->sDefaultPreviewSlug !== $oPost->post_type ) {
            return $sOutput;
        }
        return $this->_replaceWithUserSetPostTypeSlug( $sOutput );
    }

    /**
     * Creates a custom post type for preview pages.
     * 
     * If this is not created, the default plugin unit post type will be used.
     * And if this is created, the default one will be publicly disabled except some admin ui functionality.
     * 
     * @callback        action      init
     * @since         1
     * @return          void
     */
    public function _replyToRegisterCustomPreviewPostType() {

        $_oOption = Externals_Option::getInstance();
        register_post_type(
            $this->sPreviewPostTypeSlug,
            array(            // argument - for the array structure, refer to http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels' => array(
                    'name'                  => Externals_Registry::NAME,
                    'singular_name'         => __( 'External', 'externals' ),
                    'menu_name'             => Externals_Registry::NAME,    // this changes the root menu name so cannot simply put Manage External here
                    'add_new'               => __( 'Add New External by Category', 'externals' ),
                    'add_new_item'          => __( 'Add New External', 'externals' ),
                    'edit'                  => __( 'Edit', 'externals' ),
                    'edit_item'             => __( 'Edit External', 'externals' ),
                    'new_item'              => __( 'New External', 'externals' ),
                    'view'                  => __( 'View', 'externals' ),
                    'view_item'             => __( 'View Product Links', 'externals' ),
                    'search_items'          => __( 'Search Externals', 'externals' ),
                    'not_found'             => __( 'No unit found for Externals', 'externals' ),
                    'not_found_in_trash'    => __( 'No External Found for Externals in Trash', 'externals' ),
                    'parent'                => 'Parent External'
                ),
                'public'                => true,
                'show_ui'               => false,
                'publicly_queryable'    => $_oOption->isPreviewVisible(),
            )        
        );
            
    }    
        /**
         * 
         * @since         1
         * @callback    filter      post_link
         * @param       string      $sPermalink     The post's permalink.
         * @param       WP_Post     $oPost          The post in question.
         * @param       boolean     $bLeaveName     Whether to keep the post name.
         * @return      string
         */
        public function _replyToModifyPermalinks( $sPermalink, $oPost, $bLeaveName ) {

            if ( $this->sDefaultPreviewSlug !== $oPost->post_type ) {
                return $sPermalink;
            }

            return $this->_replaceWithUserSetPostTypeSlug( $sPermalink );
            
        }    
           
        /**
         * 
         * @since         1
         * @return      string
         */
        private function _replaceWithUserSetPostTypeSlug( $sSubject ) {
            return str_replace(
                array( 
                    '/' . $this->sDefaultPreviewSlug . '/',
                    'post_type=' . $this->sDefaultPreviewSlug,
                    $this->sDefaultPreviewSlug . '='
                ), // search
                array(
                    '/' . $this->sPreviewPostTypeSlug . '/',
                    'post_type=' . $this->sPreviewPostTypeSlug,
                    $this->sPreviewPostTypeSlug . '='
                ), // replace
                $sSubject     // subject
            );            
        }
         
    
    /**
     * 
     * @since         1
     * @callback    filter      request
     * @return      array       The database query request array.
     */
    public function _replyToModifyDatabaseQuery( $aRequest ) {

        $_oOption = Externals_Option::getInstance();
    
        if ( ! isset( $aRequest[ 'post_type' ], $aRequest[ 'name' ] ) ) {
            return $aRequest;
        }
        if ( $this->sPreviewPostTypeSlug !== $aRequest[ 'post_type' ] ) {
            return $aRequest;
        }
        if ( ! $_oOption->isPreviewVisible() ) {
            return $aRequest;
        }
        

        $aRequest[ 'post_type' ] = $this->sDefaultPreviewSlug;
        $aRequest[ $this->sDefaultPreviewSlug ] = $aRequest[ 'name' ];
        return $aRequest;
    
    }

}