<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box that shows a button preview.
 */
class Externals_MetaBox_Button_CSS extends Externals_MetaBox_Button {

    
    public function setUp() {
        
        $this->addSettingFields(
            array(
                'field_id'      => 'button_css',
                'type'          => 'textarea',
                'title'         => __( 'Generated CSS', 'externals' ),
                'description'   => __( 'The generated CSS rules will look like this.', 'externals' ),
                'attributes'    => array(
                    'style' => 'width: 100%; height: 320px;',
                ),
            ),
            array(
                'field_id'      => 'custom_css',
                'type'          => 'textarea',
                'title'         => __( 'Custom CSS', 'externals' ),
                'description'   => __( 'Enter additional CSS rules here.', 'externals' ),
                'attributes'    => array(
                    'style' => 'width: 100%; height: 200px;',
                ),                
            )     
        );
        
        
    }
    
    /**
     * Draws the Select Category submit button and some other links.
     */
    public function replyToPrintMetaBoxConetnt( $oFactory ) {
        $_sPostTitle = isset( $_GET[ 'post' ] )
            ? get_the_title( $_GET[ 'post' ] )
            : '';
        $_sPostTitle = $_sPostTitle
            ? $_sPostTitle
            : __( 'Buy Now', 'externals' );

        ?>
        <div style="margin: 3em 0 1.5em 1em;">
            <div style="margin-left: auto; margin-right: auto; text-align:center;">
                <div class="externals-button"><?php echo $_sPostTitle; ?></div>
            </div>            
        </div>
        <?php
    }    
    
}