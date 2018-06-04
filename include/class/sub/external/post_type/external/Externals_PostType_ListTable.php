<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides methods for rendering post listing table contents.
 * 
 * @package     Externals
 * @since       1
 */
class Externals_PostType_ListTable extends Externals_AdminPageFramework_PostType {
    
    public $sCustomNonce;
    
    public function setUp() {
        
        if ( 'edit.php' === $this->oProp->sPageNow && $this->_isInThePage() ) {
        
            // listing table columns
            add_filter(    
                'columns_' . Externals_Registry::$aPostTypes[ 'external' ],
                array( $this, 'replyToModifyColumnHeader' )
            );        
            
            add_filter(
                'action_links_' . Externals_Registry::$aPostTypes[ 'external' ],
                array( $this, 'replyToModifyActionLinks' ),
                10,
                2
            );
                
            new Externals_ExternalPostType_ListTableActionHandler( $this );

        }
        
    }
    
    /**
     * Modifies the action links.
     * @callback    filter      action_links_{post type slug}     
     * @return      array
     */
    public function replyToModifyActionLinks( $aActionLinks, $oPost ) {

        $_sExternalType = get_post_meta( $oPost->ID, '_type', true );
        // http://.../wp44/wp-admin/post.php?post=294&action=edit&post_type=externals
        $_sEditHref = esc_url(
            add_query_arg(
                array(
                    'post_type' => Externals_Registry::$aPostTypes[ 'external' ],
                    'post'      => $oPost->ID,
                    'action'    => 'edit',
                    '_type'     => $_sExternalType,
                ),
                admin_url( 'post.php' )  // admin_url( strip_tags( $aActionLinks[ 'edit' ] ) )
            )
        );
        $aActionLinks[ 'edit' ] = "<a href='{$_sEditHref}'>"
                . __( 'Edit', 'externals' )
            . "</a>";
        $aActionLinks = apply_filters(
            Externals_Registry::HOOK_SLUG . '_filter_action_links_' . Externals_Registry::$aPostTypes[ 'external' ] . '_' . $_sExternalType,
            $aActionLinks,
            $oPost
        );

        return $aActionLinks;
        
    }
    
    /**
     * Defines the column header of the unit listing table.
     * 
     * @callback     filter      columns_{post type slug}
     * @return       array
     */
    public function replyToModifyColumnHeader( $aHeaderColumns ) {    
        return array(
            'cb'                    => '<input type="checkbox" />',   
            'title'                 => __( 'External Name', 'externals' ),    
            'type'                  => __( 'External Type', 'externals' ),
            'template'              => __( 'Template', 'externals' ),
            'externals_tag'         => __( 'Labels', 'externals' ),  
            'code'                  => __( 'Shortcode / PHP Code', 'externals' ),
        );                      
    }    
        
    /**
     * 
     * @callback        filter      cell_ + post type slug + column name
     * @return          string
     */
    public function cell_externals_externals_tag( $sCell, $iPostID ) {    
        
        // Get the genres for the post.
        $_aTerms = get_the_terms( 
            $iPostID, 
            Externals_Registry::$aTaxonomies[ 'tag' ]
        );
    
        // If no tag is assigned to the post,
        if ( empty( $_aTerms ) ) { 
            return '—'; 
        }
        
        // Loop through each term, linking to the 'edit posts' page for the specific term. 
        $_aOutput = array();
        foreach( $_aTerms as $_oTerm ) {
            $_aOutput[] = sprintf( 
                '<a href="%s">%s</a>',
                esc_url( 
                    add_query_arg( 
                        array( 
                            'post_type' => $GLOBALS[ 'post' ]->post_type, 
                            Externals_Registry::$aTaxonomies[ 'tag' ] => $_oTerm->slug 
                        ), 
                        'edit.php' 
                    ) 
                ),
                esc_html( 
                    sanitize_term_field( 
                        'name', 
                        $_oTerm->name, 
                        $_oTerm->term_id, 
                        Externals_Registry::$aTaxonomies[ 'tag' ], 
                        'display' 
                    ) 
                )
            );
        }

        // Join the terms, separating them with a comma.
        return join( ', ', $_aOutput );
        
    }
    /**
     * @callback        filter      cell_{post type slug}_{column key}
     */
    public function cell_externals_type( $sCell, $iPostID ) {
        
        $_sExternalType       = get_post_meta( $iPostID, '_type', true );
        return "<p>"
            . apply_filters( 
                Externals_Registry::HOOK_SLUG . '_filter_external_type_label',
                $_sExternalType 
            )
            . "</p>"
            ;
        
    }
    /**
     * @callback        filter      cell_{post type slug}_{column key}
     */
    public function cell_externals_template( $sCell, $iPostID ) {
        return "<p>"
            . Externals_TemplateOption::getInstance()->getTemplateNameByID( 
                get_post_meta( $iPostID, '_template_id', true ) // template id
            )
            . "</p>";
    }    
    
    /**
     * @callback        filter      cell_{post type slug}_{column name}
     */
    public function cell_externals_code( $sCell, $iPostID ) {
        return '<p>'
                . '<span>[externals id="' . $iPostID . '"]</span>' . '<br />'
                . '<span>&lt;?php Externals( array( ‘id’ =&gt; ' . $iPostID . ' ) ); ?&gt;</span>'
            . '</p>';
    }

}