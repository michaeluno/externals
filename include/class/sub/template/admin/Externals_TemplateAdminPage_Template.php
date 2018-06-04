<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the `Templates` page.
 * 
 * @since       1
 */
class Externals_TemplateAdminPage_Template extends Externals_AdminPage_Page_Base {


    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    public function construct( $oFactory ) {
        
        // Tabs
        new Externals_TemplateAdminPage_Template_ListTable( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'table',
                'title'     => __( 'Installed', 'externals' ),
            )
        );
        new Externals_TemplateAdminPage_Template_GetNew( 
            $this->oFactory,
            $this->sPageSlug,
            array( 
                'tab_slug'  => 'get',
                'title'     => __( 'Get New', 'externals' ),
            )
        );

    }   
    

        
}
