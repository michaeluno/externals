<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Loads the plugin.
 * 
 * @action      do      externals_action_after_loading_plugin
 * @since       1
 */
final class Externals_Bootstrap extends Externals_AdminPageFramework_PluginBootstrap {
    
    /**
     * User constructor.
     */
    protected function construct()  {
        
        if ( $this->bIsAdmin ) {
            $this->checkCustomTables();
        }
        
    }        
        
        /**
         * Checks if table version options exist and if not install it.
         */
        private function checkCustomTables() {
            
            $_aTableVersions = array();
            foreach( Externals_Registry::$aOptionKeys[ 'table_versions' ] as $_sOptionKey ) {
                $_aTableVersions[] = get_option( $_sOptionKey, false );
            }
            if ( ! in_array( false, $_aTableVersions, true ) ) {
                return;
            }
            
            // At this point, there is a value `false` in the array, 
            // which means there is a table that is not installed.            
            // Install tables.
            add_action( 
                'plugins_loaded', // action hook name
                array( $this, 'replyToInstallCustomTables' ), // callback 
                1       // priority
            );
            
        }
    
    
    /**
     * Installs plugin custom database tables.
     * @callback        plugins_loaded
     */
    public function replyToInstallCustomTables() {
        new Externals_DatabaseTableInstall( 
            true    // install
        );        
    }
        
    /**
     * Register classes to be auto-loaded.
     * 
     * @since       1
     */
    public function getClasses() {
        
        // Include the include lists. The including file reassigns the list(array) to the $_aClassFiles variable.
        $_aClassFiles   = array();
        $_bLoaded       = include( dirname( $this->sFilePath ) . '/include/class-list.php' );
        if ( ! $_bLoaded ) {
            return $_aClassFiles;
        }
        return $_aClassFiles;
                
    }

    /**
     * Sets up constants.
     */
    public function setConstants() {}    
    
    /**
     * Sets up global variables.
     */
    public function setGlobals() {
        
        if ( $this->bIsAdmin ) { 
        
            // The form transient key will be sent via the both get and post methods.
            $GLOBALS[ 'externals_transient_id' ] = isset( $_REQUEST[ 'transient_id' ] )
                ? $_REQUEST[ 'transient_id' ]
                : Externals_Registry::TRANSIENT_PREFIX 
                    . '_Form' 
                    . '_' . get_current_user_id() 
                    . '_' . uniqid();

        }        
        
    }    
    
    /**
     * The plugin activation callback method.
     */    
    public function replyToPluginActivation() {

        $this->_checkRequirements();
        
        $this->replyToInstallCustomTables();
               
    }
        
        /**
         * 
         * @since       1
         */
        private function _checkRequirements() {

            $_oRequirementCheck = new Externals_AdminPageFramework_Requirement(
                Externals_Registry::$aRequirements,
                Externals_Registry::NAME
            );
            
            if ( $_oRequirementCheck->check() ) {            
                $_oRequirementCheck->deactivatePlugin( 
                    $this->sFilePath, 
                    __( 'Deactivating the plugin', 'externals' ),  // additional message
                    true    // is in the activation hook. This will exit the script.
                );
            }        
             
        }    
  
        
    /**
     * The plugin activation callback method.
     */    
    public function replyToPluginDeactivation() {
        
        Externals_WPUtility::cleanTransients( 
            array( 
                Externals_Registry::TRANSIENT_PREFIX,
                'apf_'
            )
        );
        
    }        
    
        
    /**
     * Load localization files.
     * 
     * @callback    action      init
     */
    public function setLocalization() {
                
        load_plugin_textdomain( 
            Externals_Registry::TEXT_DOMAIN, 
            false, 
            dirname( plugin_basename( $this->sFilePath ) ) . '/' . Externals_Registry::TEXT_DOMAIN_PATH
        );
        
    }        
    
    /**
     * Loads the plugin specific components. 
     * 
     * @remark        All the necessary classes should have been already loaded.
     */
    public function setUp() {
        
        // This constant is set when uninstall.php is loaded.
        if ( defined( 'DOING_PLUGIN_UNINSTALL' ) && DOING_PLUGIN_UNINSTALL ) {
            return;
        }
            
        // 1. Include PHP files.
        $this->_include();
            
        // 2. Option Object - must be done before the template object.
        Externals_Option::getInstance();
       
        // Load external type components.
        $this->_loadComponents();
       
        // 3. Active template resources
        new Externals_TemplateResourceLoader;
        
        // 4. Post Types
        new Externals_PostType( 
            Externals_Registry::$aPostTypes[ 'external' ],  // slug
            null,   // post type arguments, defined inside the class.
            $this->sFilePath   // script path
        );    
            
        // 5. Admin pages
        if ( $this->bIsAdmin ) {

            new Externals_SettingAdminPage( 
                Externals_Registry::$aOptionKeys[ 'setting' ], 
                $this->sFilePath 
            );

            new Externals_TemplateAdminPage(
                Externals_Registry::$aOptionKeys[ 'template' ],
                $this->sFilePath 
            );
            
            new Externals_HelpAdminPage(
                '', // no options
                $this->sFilePath 
            );

        }

        // 9. Events
        new Externals_Event;    
        
        // 10. Outputs
        new Externals_OutputHook;
                
    }
        /**
         * Includes additional files.
         */
        private function _include() {
            
            // Functions
            include( dirname( $this->sFilePath ) . '/include/function/functions.php' );
            
        }
        
        /**
         * 
         */
        private function _loadComponents() {
            
            // External types
            new Externals_ExternalTypesLoader;

            // Shortcode - e.g. [externals id="143"]
            new Externals_Shortcode( 
                Externals_Registry::$aShortcodes 
            );            
            
            
        }
            
    
}