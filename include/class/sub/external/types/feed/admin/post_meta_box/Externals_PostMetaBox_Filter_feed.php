<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box that contains Template options.
 */
class Externals_PostMetaBox_Filter_feed extends Externals_PostMetaBox_Base {
       
    /**
     * Stores registered external type slugs that the meta box should appear.
     * 
     */
    public $aExternalTypes = array(
        'feed',
    );       
       
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
            
        $_oArgument = new Externals_ExternalArgument_feed;    

        $this->addSettingSections(
            array(
                'section_id'    => '_block_not_containing',
                'title'         => __( 'Block Items Not Containing Sub-strings', 'externals' ),
                'description'   => __( 'Block items that do not contain specified Sub-strings.', 'externals' ),
            )
        );
        $this->addSettingFields(
            '_block_not_containing',
            array(
                'type'       => 'textarea',
                'field_id'   => 'title',
                'title'      => __( 'Sub-strings in Title', 'externals' ),                
                'tip'        => array(
                    __( 'A feed item containing a title with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'default'    => $_oArgument->get( 'block_not_containing', 'title' ),
            ),
            array(
                'type'       => 'textarea',
                'field_id'   => 'description',
                'title'      => __( 'Sub-strings in Description', 'externals' ),                
                'tip'        => array(
                    __( 'A feed item containing descriptions with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'default'    => $_oArgument->get( 'block_not_containing', 'description' ),
            ),
            array(
                'type'       => 'textarea',
                'field_id'   => 'content',
                'title'      => __( 'Sub-strings in Content', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item containing content with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_not_containing', 'description' ),
            ),             
            array(
                'type'       => 'textarea',
                'field_id'   => 'author',
                'title'      => __( 'Sub-string in Author', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item whose author with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_not_containing', 'author' ),
            ),                   
            array(
                'type'       => 'textarea',
                'field_id'   => 'permalink',
                'title'      => __( 'Sub-string in Link URL', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item linking to a url with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_not_containing', 'permalink' ),
            ),            
            array(
                'type'       => 'textarea',
                'field_id'   => 'image',
                'title'      => __( 'Sub-string in Image URL', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item containing images with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_not_containing', 'image' ),
            ),
            array()            
        );
        
        $this->addSettingSections(
            array(
                'section_id'    => '_block_containing',
                'title'         => __( 'Block Items Containing Sub-strings', 'externals' ),
                'description'   => __( 'Block items that contain specified sub-strings.', 'externals' ),
            )
        );
        $this->addSettingFields(
            '_block_containing',
            array(
                'type'       => 'textarea',
                'field_id'   => 'title',
                'title'      => __( 'Sub-strings in Title', 'externals' ),                
                'tip'        => array(
                    __( 'A feed item containing a title with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'default'    => $_oArgument->get( 'block_containing', 'title' ),
            ),
            array(
                'type'       => 'textarea',
                'field_id'   => 'description',
                'title'      => __( 'Sub-strings in Description', 'externals' ),                
                'tip'        => array(
                    __( 'A feed item containing descriptions with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'default'    => $_oArgument->get( 'block_containing', 'description' ),
            ),
            array(
                'type'       => 'textarea',
                'field_id'   => 'content',
                'title'      => __( 'Sub-strings in Content', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item containing content with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_containing', 'description' ),
            ),             
            array(
                'type'       => 'textarea',
                'field_id'   => 'author',
                'title'      => __( 'Sub-string in Author', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item whose author with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_containing', 'author' ),
            ),                   
            array(
                'type'       => 'textarea',
                'field_id'   => 'permalink',
                'title'      => __( 'Sub-string in Link URL', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item linking to a url with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_containing', 'permalink' ),
            ),            
            array(
                'type'       => 'textarea',
                'field_id'   => 'image',
                'title'      => __( 'Sub-string in Image URL', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'tip'        => array(
                    __( 'A feed item containing images with this sub-string will be blocked.', 'externals' ),                
                    __( 'For multiple sub-strings, set one per line.', 'externals' ),                
                ),
                'default'    => $_oArgument->get( 'block_containing', 'image' ),
            ),
            array()            
        );

        $this->addSettingSections(
            array(
                'section_id'    => '_blacklist_image',
                'title'         => __( 'Image Blacklist', 'externals' ),
                'description'   => __( 'Block images by sub-string.', 'externals' ),
            )
        );
        $this->addSettingFields(
            '_blacklist_image',
            array(
                'type'       => 'textarea',
                'field_id'   => 'src',
                'title'      => __( 'Substring in Image URL', 'externals' ),                
                'attributes' => array(
                    'wrap'  => 'off',
                    'style' => 'min-width: 100%; overflow: auto;',
                    'field' => array(
                        'style' => 'width: 100%',
                    ),
                ),                
                'default'    => $_oArgument->get( 'blacklist_image', 'src' ),
            ),
            array()            
        );
        
    }
     
    /**
     * Validates submitted form data.
     */
    public function validate( /* $_aInputs, $aOriginal, $oFactory */ ) {    
                
        // $_bVerified = true;
        // $_aErrors   = array();        
        
        $_aParams    = func_get_args() + array( null, null, null );
        $_aInputs    = $_aParams[ 0 ];
        // $_aOldInputs = $_aParams[ 1 ];
                
        // if ( ! $_bVerified ) {
            
            // $this->setFieldErrors( $_aErrors );
            // $this->setSettingNotice( 
                // __( 'There is an error in your inputs.', 'externals' )
            // );
            // return $_aOldInputs;            
            
        // }

        return $_aInputs;
        
    } 
    
}