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
class Externals_AdminPage extends Externals_AdminPageFramework {

    /**
     * User constructor.
     */
    public function start() {
        
        if ( ! $this->oProp->bIsAdmin ) {
            return;
        }     
        add_filter( 
            "options_" . $this->oProp->sClassName,
            array( $this, 'replyToSetOptions' )
        );
        
           
    }
        /**
         * Sets the default option values for the setting form.
         * @return      array       The options array.
         */
        public function replyToSetOptions( $aOptions ) {
            $_oOption    = Externals_Option::getInstance();
            return $aOptions + $_oOption->aDefault; 
        }
        
    /**
     * Sets up admin pages.
     */
    public function setUp() {

        $this->setRootMenuPageBySlug( 'edit.php?post_type=' . Externals_Registry::$aPostTypes[ 'external' ] );
       
        // $this->_doPageSettings();

    }

        /**
         * Page styling
         * @since       1
         * @return      void
         */
        private function _doPageSettings() {
                        
            $this->setPageTitleVisibility( false ); // disable the page title of a specific page.
            $this->setInPageTabTag( 'h2' );                
            // $this->setPluginSettingsLinkLabel( '' ); // pass an empty string to disable it.
            $this->addLinkToPluginDescription(
                "<a href='https://wordpress.org/support/plugin/externals' target='_blank'>" . __( 'Support', 'externals' ) . "</a>"
            );         

            $this->enqueueStyle( Externals_Registry::getPluginURL( 'asset/css/admin.css' ) );

            $this->setDisallowedQueryKeys( array( 'aal-option-upgrade', 'bounce_url' ) );            
        
        }
    
   
        
}