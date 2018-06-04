<?php
/**
 * Provides the form fields definitions.
 * 
 * @since       1  
 */
class Externals_FormFields_Widget_Visibility extends Externals_FormFields_Base {

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
                'field_id'      => $sFieldIDPrefix. 'width',
                'type'          => 'number',
                'title'         => __( 'Width', 'externals' ),
                'default'       => 100,
            ), 
            array(
                'field_id'      => $sFieldIDPrefix .'width_unit',
                'type'          => 'select',
                'show_title_column' => false,
                // 'title'         => __( 'Width External', 'externals' ),
                'label'         => array(
                    'px'    => 'px', 
                    '%'     => '%', 
                    'em'    => 'em'
                ),                
                'default'       => '%',
                'description'   => __( 'Set 0 for no limit.', 'externals' ),
            ),
            array(
                'field_id'      => $sFieldIDPrefix . 'height',
                'type'          => 'number',
                'title'         => __( 'Height', 'externals' ),
                'default'       => 400,
            ),       
            array(
                'field_id'      => $sFieldIDPrefix . 'height_unit',
                'type'          => 'select',
                'show_title_column' => false,
                // 'title'         => __( 'Height External', 'externals' ),
                'label'         => array(
                    'px'    => 'px', 
                    '%'     => '%', 
                    'em'    => 'em'
                ),                
                'default'       => 'px',
                'description'   => __( 'Set 0 for no limit.', 'externals' ),                
            ),            
            array(
                'field_id'      => $sFieldIDPrefix . 'available_page_types',
                'type'          => 'checkbox',
                'title'         => __( 'Available Page Types', 'externals' ),
                'label'         => array(
                    'singular'          => __( 'Single pages.', 'externals' ),
                    'post_type_archive' => __( 'Post type archive pages.', 'externals' ),
                    'taxonomy'          => __( 'Taxonomy archive pages.', 'externals' ),
                    'date'              => __( 'Date archive pages.', 'externals' ),
                    'author'            => __( 'Author pages.', 'externals' ),
                    'search'            => __( 'Search result pages.', 'externals' ),
                    '404'               => __( 'The 404 page.', 'externals' ),
                ),
                'default'       => array(
                    'singular'          => true,
                    'post_type_archive' => false,
                    'taxonomy'          => false,
                    'date'              => false,
                    'author'            => false,
                    'search'            => false,
                    '404'               => false,
                ),
            ),                                  
            array()
        );

       
        return $_aFields;
        
    }
      
}