<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box that shows the Select Categories submit button
 */
class Externals_MetaBox_CategoryExternal_Submit extends Externals_PostMetaBox_Base {

    /**
     * Stores the unit type slug(s). 
     */    
    protected $aExternalTypes = array( 'category' );
    
    public function setUp() {
        
        if ( ! isset( $_GET[ 'post' ] ) ) {
            return;
        }        
        add_action(
            "do_" . $this->oProp->sClassName,
            array( $this, 'printMetaBoxConetnt' )
        );
        
    }
    
    /**
     * Draws the Select Category submit button and some other links.
     */
    public function printMetaBoxConetnt( $sContent ) {
        
        $_sViewLink              = esc_url( get_permalink( $_GET[ 'post' ] ) );
        $_sSelectCategoryPageURL = add_query_arg( 
            array( 
                'mode'          => 0,
                'page'          => Externals_Registry::$aAdminPages[ 'category_select' ],
                'tab'           => 'second',
                'post_type'     => Externals_Registry::$aPostTypes[ 'external' ],
                'post'          => $_GET[ 'post' ],
                'transient_id'  => uniqid(),
                'externals_action'    => 'select_category',
            ),
            admin_url( 'edit.php' ) 
        );
        ?>

        <div style="padding: 0.8em 0 1.5em; ">
            <div style="text-align: center;">
                <a class="button button-primary button-large" href="<?php echo esc_url( $_sSelectCategoryPageURL ); ?>">
                    <?php _e( 'Select Categories', 'externals' ); ?>
                </a>
            </div>        
        </div>
        <?php

        
        $_aCategories           = $this->oUtil->getAsArray(
            get_post_meta( 
                $_GET[ 'post' ], 
                'categories', 
                true 
           )
        );
        $_aExcludingCategories  = $this->oUtil->getAsArray(
            get_post_meta( 
                $_GET[ 'post' ], 
                'categories_exclude', 
                true 
            )
        );
       
        $_aCategoryList = array();
        foreach( $_aCategories as $_aCategory ) {
            $_aCategoryList[] = "<li style=''>" 
                    . $_aCategory[ 'breadcrumb' ] 
                . "</li>";
        }
        $_aExcludingCategoryList = array();
        foreach( $_aExcludingCategories as $_aCategory ) {
            $_aExcludingCategoryList[] = "<li style=''>" 
                    . $_aCategory['breadcrumb'] 
                . "</li>";            
        }
        
        if ( empty( $_aCategoryList ) ) {
            $_aCategoryList[] = "<li>" 
                    . __( 'No category added.', 'externals' ) 
                . "</li>";
        }
        if ( empty( $_aExcludingCategoryList ) ) {
            $_aExcludingCategoryList[] = "<li>" 
                    . __( 'No excluding sub-category added.', 'externals' ) 
                . "</li>";
        }
            
        echo "<h4 style='text-align: center'>" 
                . __( 'Added Categories', 'externals' ) 
            . "</h4>"
            . "<div style='text-align: center; font-weight: normal; padding-bottom: 0.5em;'>"
                . "<ul style='margin-left:0;'>" 
                    . implode( '', $_aCategoryList ) 
                . "</ul>"
            . "</div>"
            . "<h4 style='text-align: center'>" 
                . __( 'Added Excluding Sub-categories', 'externals' ) 
            . "</h4>"
            . "<div style='text-align: center; font-weight: normal; padding-bottom: 0.5em;'>"
                . "<ul style='margin-left:0;'>" 
                    . implode( '', $_aExcludingCategoryList )
                . "</ul>"
            . "</div>";
    
    }    
    
}