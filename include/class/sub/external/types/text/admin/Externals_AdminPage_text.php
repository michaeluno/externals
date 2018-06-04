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
class Externals_AdminPage_text extends Externals_AdminPageFramework {
        
    /**
     * Sets up admin pages.
     */
    public function setUp() {
        
        $this->setRootMenuPageBySlug( 
            'edit.php?post_type=' . Externals_Registry::$aPostTypes[ 'external' ]
        );
        
        // http://.../wp-admin/post-new.php?post_type=externals
        $this->addSubMenuItems(
            array(
                'href'          => add_query_arg(
                    array(
                        'post_type'         => Externals_Registry::$aPostTypes[ 'external' ],
                        '_type'    => 'text',
                    ),
                    admin_url( 'post-new.php' )
                ),
                'title'         => __( 'Add New Text', 'externals' ),
                // 'screen_icon'   => AmazonAutoLinks_Registry::getPluginURL( "asset/image/screen_icon_32x32.png" ),
            )        
        );
     
        // Post meta boxes
        new Externals_PostMetaBox_Main_text(
            null, // meta box ID - null to auto-generate
            __( 'Main', 'externals' ) . ' - ' . __( 'Text', 'externals' ),
            array( // post type slugs: post, page, etc.
                Externals_Registry::$aPostTypes[ 'external' ] 
            ), 
            'normal', // context (what kind of metabox this is)
            'core' // priority             
        );
     
    }
        
}