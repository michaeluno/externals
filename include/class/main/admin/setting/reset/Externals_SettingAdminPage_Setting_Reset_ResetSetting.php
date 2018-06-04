<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 */

/**
 * Adds the 'Capability' form section to the 'Misc' tab.
 * 
 * @since       1
 */
class Externals_SettingAdminPage_Setting_Reset_RestSettings extends Externals_AdminPage_Section_Base {
    
    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    protected function construct( $oFactory ) {
             
        // reset_{instantiated class name}_{section id}_{field id}
        add_action( 
            "reset_{$oFactory->oProp->sClassName}_{$this->sSectionID}_all",
            array( $this, 'replyToResetOptions' ), 
            10, // priority
            4 // number of parameters
        );
        
    }
    
    /**
     * Adds form fields.
     * @since       1
     * @return      void
     */
    public function addFields( $oFactory, $sSectionID ) {

       $oFactory->addSettingFields(
            $sSectionID, // the target section id
            array( 
                'field_id'          => 'all',
                'title'             => __( 'Restore Default', 'externals' ),
                'type'              => 'submit',
                'reset'             => true,
                'value'             => __( 'Restore', 'externals' ),
                'description'       => __( 'Restore the default options.', 'externals' ),
                'attributes'        => array(
                    'size'          => 30,
                    // 'required' => 'required',
                ),
            ),
            array(
                'field_id'          => 'reset_on_uninstall',
                'title'             => __( 'Delete Options upon Uninstall', 'externals' ),
                'type'              => 'checkbox',
                'label'             => __( 'Delete options and caches when the plugin is uninstalled.', 'externals' ),
            )           
        );          
            
    
    
    }
        
    public function replyToResetOptions( $asKeyToReset, $aInput, $oFactory, $aSubmitInfo ) {
        
        // Delete the template options as well.
        delete_option(
            Externals_Registry::$aOptionKeys[ 'template' ]
        );
           
    }
    
   
}