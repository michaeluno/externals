<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates Amazon product links by ItemSearch.
 * 
 * @package         Externals
 */
class Externals_ExternalOutput_wp_readme extends Externals_ExternalOutput_Base {
    
    /**
     * Stores the unit type.
     * @remark      Note that the base constructor will create a unit option object based on this value.
     */    
    public $sExternalType = 'wp_readme';
    
    /**
     * Represents the array structure of the item array element of API response data.
     * @since            unknown
     */    
    public static $aStructure_Item = array();
    
    public function construct() {}
    
    
    /**
     * 
     * @return    array    The response array.
     */
    public function getItems( $aURLs=array() ) {

        $_aItems = parent::getItems( $aURLs );
        
        // Do something specific to this external type.
        foreach( $_aItems as $_isIndex => $_aItem ) {
            if ( ! isset( $_aItem[ 'content' ] ) ) {
                continue;
            }
            $_aItems[ $_isIndex ][ 'content' ] = $this->_getContent( $_aItem[ 'content' ] );
        }
                
        return $_aItems;
        
    }
        /**
         * @return      string
         */
        private function _getContent( $sContent ) {
            
            return $this->_getReadMeParsed( 
                $sContent, 
                $this->_getSectionNamesToExtract()
            );
            
        }    
            /**
             * @return      array
             */
            private function _getSectionNamesToExtract() {
                
                $_aSections = $this->getAsArray( $this->oArgument->get( 'available_sections' ) );
                $_aSections = array_filter( $_aSections );
                $_aSections = array_keys( $_aSections );
                $_aSections = array_merge(
                    $_aSections,
                    array_filter( $this->getAsArray( $this->oArgument->get( 'custom_sections' ) ) )
                );         
                return $_aSections;
                
            }

        /**
         * @return  string
         */
        private function _getReadMeParsed( $sContent, $aSections ) {
                                    
            $_sPrintID    = $this->_getPrintID();
            $this->oArgument->set( array( 'element_id' ), $_sPrintID );
            $_sOutputType = $this->oArgument->get( 'output_type' );
            $_sOutputType = $_sOutputType ? $_sOutputType : 'accordion';
            $_sMethodName = '_getContentsFormatted_' . $_sOutputType;
            return "<div class='externals-wp_readme-sections' id='" . esc_attr( $_sPrintID ) . "'>"
                    . force_balance_tags( 
                        $this->$_sMethodName(
                            $sContent,
                            $aSections,
                            $_sPrintID
                        )
                    )
                . "</div>";
            
        }
            /**
             * @return      string
             */
            private function _getPrintID() {
                
                $_iExternalD = $this->oArgument->get( 'id' );
                if ( $_iExternalD ) {
                    // @todo theme may be a case that the same id is called multiple times. 
                    // so implement a mechanism to avoid duplicated element id.
                    return 'externals-' . $_iExternalD;
                }
                return 'externals-' . uniqid();
                
            }
            
            private function _getContentsFormatted_normal( $sContent, $aSections, $sPrintID ) {

                $_oWPReadmeParser = new Externals_AdminPageFramework_WPReadmeParser( 
                    $sContent
                );    
                $_aOutput = array();
                foreach( $aSections as $_iIndex => $_sSection ) {
                    $_aOutput[] = "<h3>" . $_sSection . "</h3>"
                        . "<div id='{$sPrintID}-{$_iIndex}' class='externals-wp_readme-section-content'>"
                            . $_oWPReadmeParser->getSection( $_sSection )
                        . "</div>";
                        
                }        
                return implode( '', $_aOutput );                

            }
            private function _getContentsFormatted_accordion( $sContent, $aSections, $sPrintID ) {

                $_oWPReadmeParser = new Externals_AdminPageFramework_WPReadmeParser( 
                    $sContent
                );    
                $_aOutput = array();
                foreach( $aSections as $_iIndex => $_sSection ) {
                    $_aOutput[] = "<h3>" . $_sSection . "</h3>"
                        . "<div id='{$sPrintID}-{$_iIndex}' class='externals-wp_readme-section-content'>"
                            . $_oWPReadmeParser->getSection( $_sSection )
                        . "</div>";
                        
                }        
                return implode( '', $_aOutput );                
            
                return $sContent;
            }
            private function _getContentsFormatted_tabs( $sContent, $aSections, $sPrintID ) {
                // Tab list
                $_sOutput = $this->_getTabList( $aSections, $sPrintID );
                            
                // Content
                $_sOutput .= $this->_getTabSectionContents( $sContent, $aSections, $sPrintID );
                return $_sOutput;
                
            }
                
                /**
                 * @return      string
                 */
                private function _getTabList( $aSections, $sPrintID ) {
                        
                    $_sOutput = "<ul class='externals-wp_readme-section-list'>";
                    foreach( $aSections as $_iIndex => $_sSection ) {
        // @todo Make it possible to translate                 
                        $_sOutput .= "<li class=''>"
                                . "<a href='#{$sPrintID}-{$_iIndex}'>"
                                    . $_sSection
                                . "</a>"
                            . "</li>";
                    }
                    $_sOutput .= "</ul>";
                    return $_sOutput;
                }        

                /**
                 * @return      string
                 */
                private function _getTabSectionContents( $sContent, $aSections, $sPrintID ) {
                
                    $_oWPReadmeParser = new Externals_AdminPageFramework_WPReadmeParser( 
                        $sContent, 
                        array( // replacements
                            // '%PLUGIN_DIR_URL%'  => AdminPageFrameworkLoader_Registry::getPluginURL(),
                            // '%WP_ADMIN_URL%'    => admin_url(),
                        ),
                        array( // callbacks
                            // 'content_before_parsing' => array( $this, '_replyToProcessShortcodes' ),
                        )
                    );    

                    $_aOutput = array();
                    foreach( $aSections as $_iIndex => $_sSection ) {
                        $_aOutput[] = "<div id='{$sPrintID}-{$_iIndex}' class='externals-wp_readme-section-content'>"
                                . $_oWPReadmeParser->getSection( $_sSection )
                            . "</div>";
                            
                    }        
                    return implode( '', $_aOutput );

                }
            
}