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
class Externals_PostMetaBox_ViewLink extends Externals_PostMetaBox_Base {
    
    public function setUp() {
        
        if ( ! isset( $_GET[ 'post' ] ) ) {
            return;
        }        
        add_action(
            "do_" . $this->oProp->sClassName,
            array( $this, 'replyToPrintMetaBoxConetnt' )
        );
        
    }
    
    /**
     * Draws the Select Category submit button and some other links.
     */
    public function replyToPrintMetaBoxConetnt( $oFactory ) {
        
        $_sViewLink              = esc_url( get_permalink( $_GET[ 'post' ] ) );
        ?>

        <div style="padding: 0.8em 0 1.5em; ">
            <div style="text-align: center;">
                <p style="font-size: 1.2em; margin-bottom: 1.5em;"><a style="text-decoration: none;" href="<?php echo $_sViewLink; ?>"><?php _e( 'View External', 'admin-page-framewor' ); ?></a></p>
            </div>            
        </div>
        <?php
    
    }    
    
}