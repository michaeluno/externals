<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Defines the meta box that contains common advanced unit options.
 */
class Externals_MetaBox_External_ProductFilter extends Externals_PostMetaBox_Base {
    
    /**
     * Stores the unit type slug(s). 
     * 
     * @remark      The meta box will not be added to a unit type not listed in this array.
     */    
    protected $aExternalTypes = array( 
        'category', 
        'similarity_lookup',
        'item_lookup',
        'search',
        'tag',        
    );    
    
    /**
     * Sets up form fields.
     */ 
    public function setUp() {
        
        $_sSectionID = 'product_filters';
        
        $this->addSettingSections(
            array(
                'section_id'    => $_sSectionID,
                'description'   => array(
                    __( 'Set the criteria to filter fetched items.', 'externals' ),
                ),
            )
        );
        
        $_oFields    = new Externals_FormFields_Setting_ProductFilter;
        foreach( $_oFields->get() as $_aField ) {
            $this->addSettingFields(
                $_sSectionID, // the target section id,
                $_aField
            );
        }        
                    
    }
    
    /**
     * Validates submitted form data.
     */
    public function validate( $aInput, $aOriginal, $oFactory ) {    
        return $aInput;        
    }
    
}