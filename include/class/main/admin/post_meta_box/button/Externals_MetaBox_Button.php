<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box for the button post type.
 */
abstract class Externals_MetaBox_Button extends Externals_AdminPageFramework_MetaBox {

    
    public function start() {
    
        // Register custom field types
        new Externals_RevealerCustomFieldType(
            $this->oProp->sClassName
        );
        
        add_action(
            "set_up_" . $this->oProp->sClassName,
            array( $this, 'replyToInsertCustomStyleTag' )
        );

    }
        
    /**
     * Indicates whether the custom style tag was inserted in the head tag or not.
     */
    static $bCustomStyleLoaded = false;
    
    /**
     * 
     * @callback        action      set_up_{instantiated class name}
     */
    public function replyToInsertCustomStyleTag() {
                    
        if ( self::$bCustomStyleLoaded ) {
            return;
        }
        self::$bCustomStyleLoaded = true;
        
        add_action( 
            'admin_head',  
            array( $this, 'replyToPrintCustomStyleTag' )
        );
          
        add_action( 
            'admin_head', 
            array( $this, 'replyToSetScripts' )
        );
        
    }
        /**
         * 
         * @callback        action      admin_head
         */
        public function replyToPrintCustomStyleTag() {
            // echo "<style type='text/css'></style>";
            echo "<style type='text/css' id='externals-button-style'>" . PHP_EOL
                    . '.externals-button {}' . PHP_EOL
                . "</style>";                    
                
        }
        
        /**
         * 
         * @callback        action      admin_head      For unknown reasons, `wp_enqueue_scripts` does not work.
         */        
        public function replyToSetScripts() {
            
            $this->enqueueScript(
                Externals_Registry::$sDirPath . '/asset/js/button-preview-event-binder.js',
                $this->oProp->aPostTypes,
                array(  
                    'handle_id'    => 'externals_button_script_event_binder',                
                    'dependencies' => array( 'jquery' )
                )
            );
            
            $this->enqueueScript(
                Externals_Registry::$sDirPath . '/asset/js/button-preview-updator.js',
                $this->oProp->aPostTypes,
                array(  
                    'handle_id'    => 'externals_button_script_preview_updator',
                    'dependencies' => array( 'jquery' ),
                    'translation'  => array(
                        'post_id' => isset( $_GET[ 'post' ] )
                            ? $_GET[ 'post' ]
                            : '___button_id___',
                    ),
                )
            ); 
                        
        }        
    
    
}