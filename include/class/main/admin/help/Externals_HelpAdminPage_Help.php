<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the `Help` page.
 * 
 * @since       1
 */
class Externals_HelpAdminPage_Help extends Externals_AdminPage_Page_Base {


    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    public function construct( $oFactory ) {
        
        $_oOption = Externals_Option::getInstance();
        
        // Tabs
        new Externals_HelpAdminPage_Help_Support( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'support',
                'title'     => __( 'Support', 'externals' ),
            )
        );        
        new Externals_HelpAdminPage_Help_FAQ( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'faq',
                'title'     => __( 'FAQ', 'externals' ),
            )
        );
        new Externals_HelpAdminPage_Help_Tips( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'tips',
                'title'     => __( 'Tips', 'externals' ),
            )
        );   
        new Externals_HelpAdminPage_Help_ChangeLog(
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'change_log',
                'title'     => __( 'Change Log', 'externals' ),
            )        
        );

    }   
    
    /**
     * 
     * @callback        action      do_after_{page slug}
     */
    public function replyToDoAfterPage( $oFactory ) {
    }
        
}
