<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the 'Add New External' page.
 * 
 * @since       1
 */
class Externals_AddNewExternalAdminPage_TypeSelect extends Externals_AdminPage_Page_Base {

    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    public function construct( $oFactory ) {
        
        // Tabs
        new Externals_AddNewExternalAdminPage_TypeSelect_Select( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'      => 'select',
                'title'         => __( 'External Type', 'externals' ),
                'description'   => __( 'Select an external type.', 'externals' ),
            )
        );
       
        $this->_doPageSettings();
        
    }   
    
        private function _doPageSettings() {
            
            $this->oFactory->setPageHeadingTabsVisibility( true );       
            $this->oFactory->setPageTitleVisibility( true ); 
            $this->oFactory->setInPageTabsVisibility( false );
            
        }
 
    public function replyToDoPage( $oFactory ) {}
    public function replyToDoAfterPage( $oFactory ) {
        $_oOption = Externals_Option::getInstance();
        if ( ! $_oOption->isDebugMode() ) {
            return;
        }        
        echo "<h3>" 
                . __( 'Debug', 'externals' ) 
                . ": " . __( 'Form Options', 'externals' )
            . "</h3>"
            . $oFactory->oDebug->get( 
                $oFactory->oProp->aOptions 
            );
      
    }
}
