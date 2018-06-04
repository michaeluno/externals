<?php

if ( ! class_exists( 'Externals_PluginUtility' ) ) {
    return;
}

class Externals_Script_ReadMeTemplate extends Externals_PluginUtility {
    
    /**
     * Stores element arguments  by element id.
     */
    static public $_aElementArguments_tabs = array();
    static public $_aElementArguments_accordion = array();
    
    /**
     * Sets up hooks.
     */
    public function __construct( $isElementID, $aArguments=array(), $sType='tabs' ) {
        
        $sType        = $sType ? $sType : 'accordion';
        if ( 'normal' === $sType ) {
            return;
        }
        $_sMethodName = 'enqueueScripts_' . $sType;
        if ( ! did_action( 'wp_enqueue_scripts' ) ) {
            add_action( 'wp_enqueue_scripts', array( $this, $_sMethodName ) );
        } else {
            $this->$_sMethodName();
        }
        $_sPropertyName = "_aElementArguments_" . $sType;
        self::${$_sPropertyName}[ $isElementID ] = $aArguments;
                
    }
        public function enqueueScripts_normal() {
        }
        public function enqueueScripts_accordion() {
            
            if ( $this->hasBeenCalled( __METHOD__ ) ) {
                return;
            }        

            wp_enqueue_script(
                'externals-wp_readme-accordion', // handle name
                Externals_Registry::getPluginURL( 'template/wp_readme/js/accordion.js' ), // source url
                array( 
                    'jquery',
                    'jquery-ui-core',
                    'jquery-ui-accordion',
                ),      // dependencies
                false,  // version
                true    // in footer
            );        
            $this->_enqueueStyle();            

            add_action( 'wp_footer', array( $this, 'replyToSetTranslation_accordion' ) );
            
        }            
        public function enqueueScripts_tabs() {
            
            if ( $this->hasBeenCalled( __METHOD__ ) ) {
                return;
            }        
             
            wp_enqueue_script(
                'externals-wp_readme-tabs', // handle name
                Externals_Registry::getPluginURL( 'template/wp_readme/js/tabs.js' ), // source url
                array( 
                    'jquery',
                    'jquery-ui-core',
                    'jquery-ui-tabs',
                ),      // dependencies
                false,  // version
                true    // in footer
            );        
            $this->_enqueueStyle();
            
            add_action( 'wp_footer', array( $this, 'replyToSetTranslation_tabs' ) );
            
        }
        
        
        public function replyToSetTranslation_accordion() {
            wp_localize_script( 
                'externals-wp_readme-accordion',  // handle id - the above used enqueue handle id
                'externals_wp_readme_accordion',  // name of the data loaded in the script
                self::$_aElementArguments_accordion // translation array
            );                           
        }
        public function replyToSetTranslation_tabs() {
            wp_localize_script( 
                'externals-wp_readme-tabs',  // handle id - the above used enqueue handle id
                'externals_wp_readme_tabs',  // name of the data loaded in the script
                self::$_aElementArguments_tabs // translation array
            );                           
        }
            /**
             * 
             */
            private function _enqueueStyle() {                
                wp_enqueue_style( 
                    'jquery-ui-extra',
                    $this->isDebugMode()
                        ? Externals_Registry::getPluginURL( '/template/wp_readme/css/jquery-ui.css' )
                        : Externals_Registry::getPluginURL( '/template/wp_readme/css/jquery-ui.min.css' )
                );
            }        
    
}
