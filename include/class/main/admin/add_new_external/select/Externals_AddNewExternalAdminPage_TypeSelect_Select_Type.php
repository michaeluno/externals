<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 */

/**
 * Adds the 'External Types' form section to the 'Add New External' tab.
 * 
 * @since       1
 */
class Externals_AddNewExternalAdminPage_TypeSelect_Select_Type extends Externals_AdminPage_Section_Base {
    
    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    protected function construct( $oFactory ) {
    }
    
    /**
     * Adds form fields.
     * @since       1
     * @return      void
     */
    public function addFields( $oFactory, $sSectionID ) {
        
        
        $_aFields                 = array(
            array(
                'field_id'      => 'post_title',
                'title'         => __( 'Name', 'externals' ),
                'type'          => 'text',
                'description'   => 'e.g. <code>My External</code>',
            ),
            array(
                'field_id'          => 'type',
                'title'             => __( 'Type', 'externals' ),
                'type'              => 'radio',
                'label'             => array(
                    'text'      => __( 'Text', 'externals' ) . ' - ' . __( 'CSS files etc.', 'externals' ),
                    'html'      => __( 'HTML', 'externals' ) . ' - ' . __( 'HTML documents.', 'externals' ),
                    'markdown'  => __( 'Markdown Text', 'externals' ) . ' - ' . __( 'Markdown readme documents which need to be converted to HTML.', 'externals' ),
                    'wp_readme' => __( 'WordPress Readme Text', 'externals' ) . ' - ' . __( 'WordPress specific readme markdown document.', 'externals' ),
                    'code'      => __( 'Code', 'externals' ) . ' - ' . __( 'to display code by specifying line numbers.', 'externals' ),
                    'image'     => __( 'Image', 'externals' ) . ' - ' . __( 'jpg, gif, png files.', 'externals' ),
                ),
                'label_min_width'   => '100%',
                'default'           => 'text',
            ),  
            array(
                'field_id'          => '_submit',
                'type'              => 'submit',            
                'value'             => __( 'Proceed', 'externals' ),
                'label_min_width'   => '',
                'attributes'        => array(
                    'field' => array(
                        'style'     => 'float: right;',
                    ),
                ),
            ),
        );
        
        foreach( $_aFields as $_aField ) {
            $oFactory->addSettingFields(
                $sSectionID, // the target section id    
                $_aField
            );
        }
        
    }
        
    
    /**
     * Validates the submitted form data.
     * 
     * @callback    filter      validation_{class name}_{section id}
     * @since       1
     */
    public function validate( $aInput, $aOldInput, $oAdminPage, $aSubmitInfo ) {
    
        $_bVerified = true;
        $_aErrors   = array();

               
        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oAdminPage->setFieldErrors( $_aErrors );     
            $oAdminPage->setSettingNotice( __( 'There was an error in your input.', 'externals' ) );
            return $aInput;
        }
        
        // Unnecessary items
        unset(
            $aInput[ '_submit' ]
        );
        
        $_iPostID = $this->_createExternal( $aInput );
        
        // Succeeded
        if ( $_iPostID ) {            
            $this->_goToPostEditingPage( $_iPostID );
            exit();
        }
        
        // Failed
        $oAdminPage->setSettingNotice( __( 'An external could not be created.', 'externals' ) );
        return $aInput;
        
        
    }   
        /**
         * @return      integer     The created post id.
         */
        private function _createExternal( $aInput ) {
            return Externals_PluginUtility::createPost(
                Externals_Registry::$aPostTypes[ 'external' ], // post type slug
                $aInput            
            );
        }
        
        private function _goToPostEditingPage( $iPostID ) {
            
            Externals_PluginUtilityh::goToPostDefinitionPage(
                $iPostID,
                Externals_Registry::$aPostTypes[ 'external' ] // post type slug
            );
            
        }
    
}