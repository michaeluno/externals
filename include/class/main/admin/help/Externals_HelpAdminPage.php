<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Adds the `Tools` page.
 * 
 * @since       1
 */
class Externals_HelpAdminPage extends Externals_AdminPageFramework {

    /**
     * User constructor.
     */
    public function start() {  
    }

    /**
     * Sets up admin pages.
     */
    public function setUp() {
        
        $this->setRootMenuPageBySlug( 
            'edit.php?post_type=' . Externals_Registry::$aPostTypes[ 'external' ]
        );
    
        new Externals_HelpAdminPage_Help( 
            $this,
            array(
                'page_slug' => Externals_Registry::$aAdminPages[ 'help' ],
                'title'     => __( 'Help', 'externals' ),
                'order'     => 1000, // to be the last menu item
            )                
        );          
        
        $this->_doPageSettings();
    }
        
        /**
         * Page styling
         * @since       1
         * @return      void
         */
        private function _doPageSettings() {
                        
            $this->setPageTitleVisibility( false ); // disable the page title of a specific page.
            $this->setInPageTabTag( 'h2' );                
            $this->setPluginSettingsLinkLabel( '' ); // pass an empty string to disable it.
            $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/admin.css' ) );

        }
    
   
}