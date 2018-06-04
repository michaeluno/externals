<?php
/**
 * Provides the definitions of auto-insert form fields for units.
 * 
 * @since       1  
 * @remark          The admin page and meta box access it.
 */
class Externals_FormFields_External_Template extends Externals_FormFields_Base {

    /**
     * Returns field definition arrays.
     * 
     * Pass an empty string to the parameter for meta box options. 
     * 
     * @return      array
     */    
    public function get( $sFieldIDPrefix='', $sExternalType='text' ) {
        
        $_aFields       = array(
            array(
                'field_id'          => $sFieldIDPrefix . 'template_id',
                'type'              => 'select',            
                'title'             => __( 'Template Name', 'externals' ),
                'description'       => __( 'Sets a default template for this external item.', 'externals' ),
                'label'             => $this->oTemplateOption->getActiveTemplateLabels(),
                'default'           => $this->oTemplateOption->getDefaultTemplateIDByExternalType( $sExternalType ),
            )        
        );

        return $_aFields;
        
    }
      
}