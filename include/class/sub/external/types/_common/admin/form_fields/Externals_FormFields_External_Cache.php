<?php
/**
 * Provides the field definitions of Cache unit options.
 * 
 * @since       1  
 */
class Externals_FormFields_External_Cache extends Externals_FormFields_Base {

    /**
     * Returns field definition arrays.
     * 
     * Pass an empty string to the parameter for meta box options. 
     * 
     * @return      array
     */    
    public function get( $sFieldIDPrefix='' ) {

        return array(
            array(
                'field_id'        => $sFieldIDPrefix . 'cache_duration',
                'title'           => __( 'Cache Duration', 'externals' ),
                'description'     => __( 'The cache lifespan. For no cache, set <code>0</code>.', 'externals' ),
                'type'            => 'size',
                'units'           => array(
                    1          => __( 'Seconds', 'externals' ),
                    60         => __( 'Minutes', 'externals' ),
                    3600       => __( 'Hours', 'externals' ),
                    86400      => __( 'Days', 'externals' ),
                    31536000   => __( 'Years', 'externals' ),
                
                ),
                'default'       => array( 
                    'size' => 1, 
                    'unit' => 86400,
                ),                
                // 'default'         => 60 * 60 * 24, // 60 * 60 * 24 // one day
            )        
        );
    }
      
}