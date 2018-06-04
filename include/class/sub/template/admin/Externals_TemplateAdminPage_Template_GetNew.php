<?php
/**
 * Externals
 * 
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the 'Get Templates' tab to the 'Template' admin page.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_TemplateAdminPage_Template_GetNew extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     * 
     * @callback        load_{page_slug}_{tab slug}
     */
    public function replyToLoadTab( $oFactory ) {
        add_filter(
            'style_' . $oFactory->oProp->sClassName,
            array( $this, 'getCSS' )
        );
    }
        /**
         * @return      string
         */
        public function getCSS( $sCSS ) {
            $_oColumn = new Externals_Column(
                array(), // data
                3,  // number of columns
                'externals_' // selector prefix
            );
            return $sCSS
                . $_oColumn->getCSS();
            
        }

    /**
     * 
     * @callback        do_{page_slug}_{tab slug}
     */
    public function replyToDoTab( $oFactory ) {
        
        $_oRSS = new Externals_RSSClient(
            'http://feeds.feedburner.com/ExternalsTemplates'
        );

        echo "<h3>" 
                . __( 'Templates', 'externals' ) 
            . "</h3>";
        echo "<p>" 
                . sprintf( 
                    __( 'Want your template to be listed here? Send the file to %1$s.', 'externals' ), 
                    'wpplugins@michaeluno.jp' 
                 ) 
            . "</p>";
        
        $_aItems = $_oRSS->get();
        if ( empty( $_aItems ) ) {
            echo "<p>" 
                    . __( 'No extension has been found.', 'externals' ) 
                . "</p>";
            return;
        }

        // Format the description element.
        foreach( $_aItems as &$_aItem ) {
            $_aItem = array(
                'description' => $this->_getFormattedDescription( $_aItem ),            
            ) + $_aItem;
        }
        
        // Get the column output.
        $_oColumn = new Externals_Column(
            $_aItems, // data
            3,  // number of columns
            'externals_' // selector prefix
        );
        echo $_oColumn->get();
        
    }   

        /**
         * @return      string
         */
        private function _getFormattedDescription( $aItem ) {
            $_aAttribuetes = array(
                'href'      => $aItem[ 'link' ],
                'rel'       => 'nofollow',
                'class'     => 'button button-secondary',
                'target'    => '_blank',
                'title'     => esc_attr( __( 'Get it Now', 'externals' ) ),
            );
            return "<h4>" . $aItem[ 'title' ] . "</h4>"
                . $aItem[ 'description' ] 
                . "<div class='get-now'>"
                    . "<a " . Externals_WPUtility::generateAttributes( $_aAttribuetes ) . ">" 
                        . __( 'Get it Now', 'externals' )
                    . "</a>"
               . "</div>";
        }
        
}