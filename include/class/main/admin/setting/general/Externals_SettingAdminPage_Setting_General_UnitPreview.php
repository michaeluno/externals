<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 */

/**
 * Adds the 'External Preview' form section to the 'General' tab.
 * 
 * @since       1
 */
class Externals_SettingAdminPage_Setting_General_ExternalPreview extends Externals_AdminPage_Section_Base {
    
    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    protected function construct( $oFactory ) {}
    
    /**
     * Adds form fields.
     * @since       1
     * @return      void
     */
    public function addFields( $oFactory, $sSectionID ) {
    
        $oFactory->addSettingFields(
            $sSectionID, // the target section id
            array(
                'field_id'       => 'preview_post_type_slug',
                'title'          => __( 'Post Type Slug', 'externals' ),
                'description'    => __( 'Up to 20 characters with small-case alpha numeric characters.', 'externals' )
                    . ' ' . __( 'Default', 'externals' )
                    . ': <code>'
                        . Externals_Registry::$aPostTypes[ 'external' ]
                    . '</code>',
                'type'           => 'text',
                'default'        => Externals_Registry::$aPostTypes[ 'external' ],
            ),
            array(
                'type'           => 'checkbox',
                'field_id'       => 'visible_to_guests',
                'title'          => __( 'Visibility', 'externals' ),                
                'label'          => __( 'Visible to non-logged-in users.', 'externals' ),
                'default'        => true,
            ),
            array(
                'type'           => 'checkbox',
                'field_id'       => 'searchable',
                'title'          => __( 'Searchable', 'externals' ),                
                'label'          => __( 'Possible for the WordPress search form to find the plugin preview pages.', 'externals' ),
                'default'        => false,
            )  
        );
    
    }
        
    
    /**
     * Validates the submitted form data.
     * 
     * @since       1
     */
    public function validate( $aInput, $aOldInput, $oAdminPage, $aSubmitInfo ) {
    
        $_bVerified = true;
        $_aErrors   = array();
        
        // Sanitize the custom preview slug.
        $aInput[ 'preview_post_type_slug' ] = Externals_Utility::getCharsForURLQueryKeySanitized(
            Externals_Utility::getTrancatedString(
                $aInput[ 'preview_post_type_slug' ],
                20, // character length
                ''  // suffix
            )   
        );

        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oAdminPage->setFieldErrors( $_aErrors );     
            $oAdminPage->setSettingNotice( __( 'There was something wrong with your input.', 'externals' ) );
            return $aOldInput;
        }
                
        return $aInput;     
        
    }
   
}