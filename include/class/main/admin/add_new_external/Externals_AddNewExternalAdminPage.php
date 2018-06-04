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
 * @since       1
 */
class Externals_AddNewExternalAdminPage extends Externals_SimpleWizardAdminPage {

    /**
     * User constructor.
     */
    public function start() {
        
        parent::start();
        
        if ( ! $this->oProp->bIsAdmin ) {
            return;
        }     
                
        // For the create new unit page. Disable the default one.
        // if ( $this->isUserClickedAddNewLink( Externals_Registry::$aPostTypes[ 'external' ] ) ) {
            // exit(
                // wp_safe_redirect(
                    // add_query_arg(
                        // array( 
                            // 'post_type' => Externals_Registry::$aPostTypes[ 'external' ],
                            // 'page'      => Externals_Registry::$aAdminPages[ 'add_new' ],
                        // ), 
                        // admin_url( 'admin.php' )
                    // )
                // )
            // );
        // }

    }

        
    /**
     * Sets the default option values for the setting form.
     * @callback    filter      `options_{class name}`
     * @return      array       The options array.
     */
    public function setOptions( $aOptions ) {
return $aOptions;
        $_aExternalOptions = array();
        if ( isset( $_GET[ 'post' ] ) ) {
            $_aExternalOptions = Externals_WPUtility::getPostMeta( $_GET[ 'post' ] );
        }
        
        // Set some items for the edit mode.
        $_iMode    = ! isset( $_GET['post'] ); // 0: edit, 1: new
        $_aOptions = array(
            'mode'       => $_iMode,
        );
        if ( ! $_iMode ) {
            $_aOptions[ 'bounce_url' ] = Externals_WPUtility::getPostDefinitionEditPageURL(
                $_GET[ 'post' ],  // post id
                Externals_Registry::$aPostTypes[ 'external' ]
            );
        }
        
        return $aOptions 
            + $_aOptions
            + $_aExternalOptions;
        
    }
        
    /**
     * Sets up admin pages.
     */
    public function setUp() {
        
        // $this->setRootMenuPageBySlug( 'Externals_AdminPage' );
        $this->setRootMenuPageBySlug( 
            'edit.php?post_type=' . Externals_Registry::$aPostTypes[ 'external' ]
        );
                    
        // Add pages
        new Externals_AddNewExternalAdminPage_TypeSelect( 
            $this,
            array(
                'page_slug'     => Externals_Registry::$aAdminPages[ 'add_new' ],
                'title'         => __( 'Add New External', 'externals' ),
                'screen_icon'   => Externals_Registry::getPluginURL( "asset/image/screen_icon_32x32.png" ),
                // 'show_in_menu'  => false,
            )
        );        
        
    }
    /**
     * Registers custom filed types of Admin Page Framework.
     */
    public function registerFieldTypes() {
        
        // @deprecated
        // new AmazonPAAPIAuthFieldType( 'Externals_AdminPage' );
        
    }
    /**
     * Page styling
     * @since       1
     * @return      void
     */
    public function doPageSettings() {
                    
        $this->setPageTitleVisibility( false ); // disable the page title of a specific page.
        $this->setInPageTabTag( 'h2' );                
        $this->setPluginSettingsLinkLabel( '' ); // pass an empty string to disable it.
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/admin.css' ) );
        
        
// @todo examine whether this is necessary or not.            
$this->setDisallowedQueryKeys( array( 'aal-option-upgrade', 'bounce_url' ) );            
return;                          
        // Action Links (plugin.php)
        // $this->addLinkToPluginTitle(
            // $_sLink1,
            // $_sLink2
        // );

        
        $this->setPageHeadingTabsVisibility( false );        // disables the page heading tabs by passing false.
                     
        $this->setInPageTabTag( 'h3', 'externals_add_category_unit' );
        $this->setInPageTabsVisibility( false, 'externals_add_category_unit' );
        $this->setInPageTabsVisibility( false, 'externals_add_search_unit' );
        $this->setInPageTabsVisibility( false, 'externals_define_auto_insert' );
                
        
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/select_categories.css' ), 'externals_add_category_unit', 'select_categories' );
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/externals_add_search_unit.css' ), 'externals_add_search_unit' );
        
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/externals_templates.css' ), 'externals_templates' );
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/readme.css' ), 'externals_about' );
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/readme.css' ), 'externals_help' );
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/get_pro.css' ), 'externals_about', 'get_pro' );
        $this->enqueueStyle( Externals_Registry::getPluginURL( 'template/preview/style-preview.css' ), 'externals_add_category_unit', 'select_categories' );

        
            
            
    }
        
}