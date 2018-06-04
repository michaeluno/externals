<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates Externals custom post type.
 * 
 * @package     Externals
 * @since       1
 * 
 * @filter      apply       externals_filter_admin_menu_name
 */
class Externals_PostType extends Externals_PostType_PostContent {
    
    // public function start() {
        

    // }
    
    public function setUp() {
        
        $_oOption = Externals_Option::getInstance();
        $this->setArguments(
            array(            // argument - for the array structure, refer to http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels' => array(
                    'name'                  => Externals_Registry::NAME,
                    'menu_name'             => apply_filters(
                        'externals_filter_admin_menu_name',
                        Externals_Registry::NAME
                    ),
                    'all_items'             => __( 'Manage Externals', 'externals' ),    // sub menu label
                    'singular_name'         => __( 'External', 'externals' ),
                    'add_new'               => __( 'Add New External', 'externals' ),
                    'add_new_item'          => __( 'Add New External', 'externals' ),
                    'edit'                  => __( 'Edit', 'externals' ),
                    'edit_item'             => __( 'Edit External', 'externals' ),
                    'new_item'              => __( 'New External', 'externals' ),
                    'view'                  => __( 'View', 'externals' ),
                    'view_item'             => __( 'View Product Links', 'externals' ),
                    'search_items'          => __( 'Search Externals', 'externals' ),
                    'not_found'             => __( 'No unit found for Externals', 'externals' ),
                    'not_found_in_trash'    => __( 'No External Found for Externals in Trash', 'externals' ),
                    'parent'                => 'Parent External',
                    
                    // framework specific keys
                    'plugin_action_link' => __( 'Manage Externals', 'externals' ),
                ),
                
                // If a custom preview post type is set, make it not public. 
                // However, other ui arguments should be enabled.
                'public'                => ! $_oOption->isCustomPreviewPostTypeSet(),
                'publicly_queryable'    => ! $_oOption->isCustomPreviewPostTypeSet()
                    && $_oOption->isPreviewVisible(),
                'has_archive'           => true,
                'show_ui'               => true,
                'show_in_nav_menus'     => true,
                'show_in_menu'          => true,

                'menu_position'         => 110,
                'supports'              => array( 'title' ),
                'taxonomies'            => array( '' ),
                // 'menu_icon'             => $this->oProp->bIsAdmin
                    // ? Externals_Registry::getPluginURL( 'asset/image/menu_icon_16x16.png' )
                    // : null,
// @todo set an icon for WP 3.7.x or below
                'menu_icon'             => 'dashicons-external',
                'hierarchical'          => false,
                'show_admin_column'     => true,
                'can_export'            => true,
                'exclude_from_search'   => ! $_oOption->get( 'external_preview', 'searchable' ),
                
                // [3.5.10+] (framework specific) default: true
                'show_submenu_add_new'  => false,
                
            )        
        );
        
        $this->addTaxonomy( 
            Externals_Registry::$aTaxonomies[ 'tag' ], 
            array(
                'labels'                => array(
                    'name'          => __( 'Label', 'externals' ),
                    'add_new_item'  => __( 'Add New Label', 'externals' ),
                    'new_item_name' => __( 'New Label', 'externals' ),
                ),
                'show_ui'               => true,
                'show_tagcloud'         => false,
                'hierarchical'          => false,
                'show_admin_column'     => true,
                'show_in_nav_menus'     => false,
                
                // Framework specific
                'show_table_filter'     => true,
                'show_in_sidebar_menus' => true,
                'submenu_order'         => 50,
            )
        );
                    
        if (  $this->_isInThePage() ) {
            
            $this->setAutoSave( false );
            $this->setAuthorTableFilter( false );            
            add_filter( 'months_dropdown_results', '__return_empty_array' );
            
            add_filter( 'enter_title_here', array( $this, 'replyToModifyTitleMetaBoxFieldLabel' ) );    
            add_action( 'edit_form_after_title', array( $this, 'replyToAddTextAfterTitle' ) );    
                
            $this->enqueueStyles(
                Externals_Registry::$sDirPath . '/asset/css/admin.css'
            );
                     
            $this->_addMetaBoxes();
            
        }
        
        parent::setUp();
                    
    }
    
        private function _addMetaBoxes() {
    
            new Externals_PostMetaBox_Common(
                null,  // meta box ID - can be null. If null is passed, the ID gets automatically generated and the class name with all lower case characters will be applied.
                __( 'Common', 'externals' ), // title
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'normal', // context (what kind of metabox this is)
                'high' // priority                                    
            );      
    
            new Externals_PostMetaBox_CommonAdvanced(
                null,  // meta box ID - can be null. If null is passed, the ID gets automatically generated and the class name with all lower case characters will be applied.
                __( 'Common Advanced', 'externals' ), // title
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'normal', // context (what kind of metabox this is)
                'low' // priority                                    
            );            
            
            // Common meta boxes
            new Externals_PostMetaBox_Cache(
                null,  // meta box ID - can be null. If null is passed, the ID gets automatically generated and the class name with all lower case characters will be applied.
                __( 'Cache', 'externals' ), // title
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'side', // context (what kind of metabox this is)
                'default' // priority                        
            );         

            new Externals_PostMetaBox_Template(
                null, // meta box ID - null to auto-generate
                __( 'Template', 'externals' ), // title
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'side', // context - e.g. 'normal', 'advanced', or 'side'
                'low' // priority - e.g. 'high', 'core', 'default' or 'low'            
            );            
            

            new Externals_PostMetaBox_ViewLink(
                null,
                __( 'View', 'externals' ), // meta box title
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'side', // context (what kind of metabox this is)
                'high' // priority                                                            
            );                 

            new Externals_PostMetaBox_DebugInfo(
                null, // meta box ID - null to auto-generate
                __( 'Debug Information', 'externals' ),
                array( // post type slugs: post, page, etc.
                    Externals_Registry::$aPostTypes[ 'external' ] 
                ), 
                'advanced', // context (what kind of metabox this is)
                'low' // priority                                        
            );   
         
            
        }
    
        
    /**
     * @callback        filter      `enter_title_here`
     */
    public function replyToModifyTitleMetaBoxFieldLabel( $strText ) {
        return __( 'Set an external name here.', 'externals' );        
    }
    /**
     * @callback        action       `edit_form_after_title`
     */
    public function replyToAddTextAfterTitle() {
        //@todo insert plugin news text headline.
    }
        
    /**
     * Style for this custom post type pages
     * @callback        filter      style_{class name}
     */
    public function style_Externals_PostType( $sCSSRules ) {
        $_sNone = 'none';
        return trim( $sCSSRules ) . PHP_EOL
            . " #post-body-content {
                margin-bottom: 10px;
            }
            #edit-slug-box {
                display: {$_sNone};
            }
            #icon-edit.icon32.icon32-posts-" . Externals_Registry::$aPostTypes[ 'external' ] . " {
                background:url('" . Externals_Registry::getPluginURL( "asset/image/screen_icon_32x32.png" ) . "') no-repeat;
            }            
            /* Hide the submit button for the post type drop-down filter */
            #post-query-submit {
                display: {$_sNone};
            }            
        ";
    }
}

