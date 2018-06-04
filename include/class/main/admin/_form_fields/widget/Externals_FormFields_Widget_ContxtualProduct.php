<?php
/**
 * Provides the form fields definitions.
 * 
 * @since       1  
 */
class Externals_FormFields_Widget_ContxtualProduct extends Externals_FormFields_Base {

    /**
     * Returns field definition arrays.
     * 
     * Pass an empty string to the parameter for meta box options. 
     * 
     * @return      array
     */    
    public function get( $sFieldIDPrefix='', $sExternalType='text' ) {
        
        $_oOption       = $this->oOption;
        $_aFields       = array(
            array(
                'field_id'      => $sFieldIDPrefix. 'title', 
                'type'          => 'text',
                'title'         => __( 'Title', 'externals' ),
            ),
            array(
                'field_id'      => $sFieldIDPrefix . 'show_title_on_no_result',
                'type'          => 'checkbox',
                'label'         => __( 'Show widget title on no result.', 'externals' ),
                'default'       => true,
            ),               
            array(
                'field_id'      => $sFieldIDPrefix . 'criteria',
                'title'         => __( 'Additional Criteria', 'externals' ),
                'type'          => 'checkbox',            
                'label'         => array(
                    'post_title'        => __( 'Post Title', 'externals' ),
                    'taxonomy_terms'    => __( 'Taxonomy Terms', 'externals' ),
                    'breadcrumb'        => __( 'Breadcrumb', 'externals' ),
                ),
                'default'       => array(
                    'post_title'        => true,
                    'taxonomy_terms'    => true,
                    'breadcrumb'        => false,
                ),
            ),            
            array(
                'field_id'      => $sFieldIDPrefix . 'additional_keywords',
                'title'         => __( 'Additional Keywords', 'externals' ),
                'type'          => 'text',
                'attributes'    => array(
                    'style' => 'width: 80%',
                ),
                'description'   => __( 'Add additional search keywords.', 'externals' ),
            ),     
            
            array(
                'field_id'          => $sFieldIDPrefix . 'country',
                'type'              => 'select',
                'title'             => __( 'Country', 'externals' ),        
                'label'         => array(                        
                    'CA' => 'CA - ' . __( 'Canada', 'externals' ),
                    'CN' => 'CN - ' . __( 'China', 'externals' ),
                    'FR' => 'FR - ' . __( 'France', 'externals' ),
                    'DE' => 'DE - ' . __( 'Germany', 'externals' ),
                    'IT' => 'IT - ' . __( 'Italy', 'externals' ),
                    'JP' => 'JP - ' . __( 'Japan', 'externals' ),
                    'UK' => 'UK - ' . __( 'Externaled Kingdom', 'externals' ),
                    'ES' => 'ES - ' . __( 'Spain', 'externals' ),
                    'US' => 'US - ' . __( 'Externaled States', 'externals' ),
                    'IN' => 'IN - ' . __( 'India', 'externals' ),
                    'BR' => 'BR - ' . __( 'Brazil', 'externals' ),
                    'MX' => 'MX - ' . __( 'Mexico', 'externals' ),
                ),
                'default'       => 'US',
            ),     
            array(
                'field_id'          => $sFieldIDPrefix . 'associate_id',
                'type'              => 'text',
                'title'             => __( 'Associate ID', 'externals' ),
                'description'       => 'e.g. ' . '<code>miunosorft-20</code>'
            ),     
            array(
                'field_id'          => $sFieldIDPrefix . 'count',
                'type'              => 'number',
                'title'             => __( 'Number of Items', 'externals' ),
                'attributes'    => array(
                    'min' => 1,
                    'max' => $_oOption->getMaximumProductLinkCount() 
                        ? $_oOption->getMaximumProductLinkCount() 
                        : null,
                ),
                'default'           => 10,
            ),     
            array(
                'field_id'          => $sFieldIDPrefix . 'image_size',
                'type'              => 'number',
                'title'             => __( 'Image Size', 'externals' ),
                'attributes'        => array(
                    'min'   => 0,
                    'max'   => 500,
                ),
                'after_input'   => ' ' . __( 'pixel', 'externals' ),
                'description'   => __( 'The maximum width of the product image in pixel. Set <code>0</code> for no image.', 'externals' )
                    . ' ' . __( 'Max', 'externals' ) . ': <code>500</code> ' 
                    . __( 'Default', 'externals' ) . ': <code>160</code>',                                
                'default'           => 160,
            ),
            array(
                'field_id'      => $sFieldIDPrefix. 'ref_nosim',
                'title'         => __( 'Direct Link Bonus', 'externals' ),
                'type'          => 'radio',
                'label'         => array(                        
                    1        => __( 'On', 'externals' ),
                    0        => __( 'Off', 'externals' ),
                ),
                'description'   => sprintf( 
                    __( 'Inserts <code>ref=nosim</code> in the link url. For more information, visit <a href="%1$s">this page</a>.', 'externals' ),
                    'https://affiliate-program.amazon.co.uk/gp/associates/help/t5/a21'
                ),
                'default'       => 0,
            ),                  
            array(
                'field_id'      => $sFieldIDPrefix. 'title_length',
                'title'         => __( 'Title Length', 'externals' ),
                'type'          => 'number',
                'description'   => __( 'The allowed character length for the title.', 'externals' ) . '&nbsp;'
                    . __( 'Use it to prevent a broken layout caused by a very long product title. Set -1 for no limit.', 'externals' ) . '<br />'
                    . __( 'Default', 'externals' ) . ": <code>-1</code>",
                'default'       => -1,
            ),        
            array(
                'field_id'      => $sFieldIDPrefix. 'link_style',
                'title'         => __( 'Link Style', 'externals' ),
                'type'          => 'radio',
                'label'         => array(                        
                    1    => 'http://www.amazon.<code>[domain-suffix]</code>/<code>[product-name]</code>/dp/<code>[asin]</code>/ref=<code>[...]</code>?tag=<code>[associate-id]</code>'
                        . "&nbsp;<span class='description'>(" . __( 'Default', 'externals' ) . ")</span>",
                    2    => 'http://www.amazon.<code>[domain-suffix]</code>/exec/obidos/ASIN/<code>[asin]</code>/<code>[associate-id]</code>/ref=<code>[...]</code>',
                    3    => 'http://www.amazon.<code>[domain-suffix]</code>/gp/product/<code>[asin]</code>/?tag=<code>[associate-id]</code>&ref=<code>[...]</code>',
                    4    => 'http://www.amazon.<code>[domain-suffix]</code>/dp/ASIN/<code>[asin]</code>/ref=<code>[...]</code>?tag=<code>[associate-id]</code>',
                    5    => site_url() . '?' . $_oOption->get( 'query', 'key' ) . '=<code>[asin]</code>&locale=<code>[...]</code>&tag=<code>[associate-id]</code>'
                ),
                'before_label'  => "<span class='links-style-label'>",
                'after_label'   => "</span>",
                'default'       => 1,
            ),        
            array(
                'field_id'      => $sFieldIDPrefix. 'credit_link',
                'title'         => __( 'Credit Link', 'externals' ),
                'type'          => 'radio',
                'label'         => array(                        
                    1   => __( 'On', 'externals' ),
                    0   => __( 'Off', 'externals' ),
                ),
                'description'   => sprintf( 
                    __( 'Inserts the credit link at the end of the unit output.', 'externals' ), 
                    '' 
                ),
                'default'       => 1,
            ),                                    
            array()
        );
        return $_aFields;
        
    }
      
}