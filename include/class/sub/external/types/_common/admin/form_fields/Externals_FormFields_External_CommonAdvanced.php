<?php
/**
 * Provides the form fields definitions.
 * 
 * @since       1  
 */
class Externals_FormFields_External_CommonAdvanced extends Externals_FormFields_Base {

    /**
     * Returns field definition arrays.
     * 
     * Pass an empty string to the parameter for meta box options. 
     * 
     * @return      array
     */    
    public function get( $sFieldIDPrefix='' ) {
        
        $_oOption       = $this->oOption;
        $_aFields       = array(

            array(
                'field_id'          => $sFieldIDPrefix . 'sslverify',
                'title'             => __( 'Verify SSL', 'externals' ),
                'type'              => 'checkbox',
                'tip'               => __( 'Checks to see if the SSL certificate is valid or not and will deny the response if it is not. If you are requesting HTTPS and know that the site is self-signed or is invalid and are reasonably sure that it can be trusted, then uncheck the option.', 'externals' ),
                'label'             => __( 'Verify the SSL certificate if the url starts with <code>https</code>.' , 'externals' ),
                'default'           => version_compare( $GLOBALS[ 'wp_version' ], '3.7', '>=' ),
            ),                
            array(
                'field_id'          => $sFieldIDPrefix . 'timeout',
                'title'             => __( 'Request Timeout', 'externals' ),
                'tip'               => __( 'The time in seconds, before the connection is dropped and an error is returned.', 'externals' ),
                'type'              => 'number',
                'description'       => __( 'Default', 'externals' ) . ' :<code>10</code>',
                'default'           => 10,
            ),
            array(
                'field_id'          => $sFieldIDPrefix . 'redirection',
                'title'             => __( 'Redirection', 'externals' ),
                'tip'               => __( 'The attempt times to follow a redirect before giving up.', 'externals' ),
                'type'              => 'number',
                'description'       => __( 'Default', 'externals' ) . ' :<code>5</code>',
                'default'           => 5,
            ), 
            
        );

        return $_aFields;
        
    }
      
}