<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates a widget by unit.
 * 
 * @since        1
 */
class Externals_WidgetByID extends Externals_AdminPageFramework_Widget {
    
    /**
     * The user constructor.
     * 
     * Alternatively you may use start_{instantiated class name} method.
     */
    public function start() {}
    
    /**
     * Sets up arguments.
     * 
     * Alternatively you may use set_up_{instantiated class name} method.
     */
    public function setUp() {

        $this->setArguments( 
            array(
                'description'   =>  __( 'Displays externals.', 'externals' ),
            ) 
        );
    
    }    

    /**
     * Sets up the form.
     * 
     * Alternatively you may use load_{instantiated class name} method.
     */
    public function load( $oAdminWidget ) {
        
        $this->addSettingFields(
            array(
                'field_id'      => 'title',
                'type'          => 'text',
                'title'         => __( 'Title', 'externals' ),
            ),
            array(
                'field_id'      => 'id',
                'type'          => 'select',
                'title'         => __( 'Externals', 'externals' ),
                'is_multiple'   => true,
                'label'         => Externals_PluginUtility::getPostTitles(
                    Externals_Registry::$aPostTypes[ 'external' ]
                ),
                'description'   => __( 'Hold down the Ctrl (windows) / Command (Mac) key to select multiple items.', 'externals' )
            )
           
        );        

        
    }
    
    /**
     * Validates the submitted form data.
     * 
     * Alternatively you may use validation_{instantiated class name} method.
     */
    public function validate( $aSubmit, $aStored, $oAdminWidget ) {
        
        // Uncomment the following line to check the submitted value.
        // AdminPageFramework_Debug::log( $aSubmit );
        
        return $aSubmit;
        
    }    
    
    /**
     * Print out the contents in the front-end.
     * 
     * Alternatively you may use the content_{instantiated class name} method.
     */
    public function content( $sContent, $aArguments, $aFormData ) {
        
        return $sContent
            . getExternals( 
                $aFormData, 
                false // echo or output
            );
    
    }
        
}