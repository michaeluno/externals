<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides methods to render the HTML elements of the category selection page in the plugin admin area.
 * 
 * This class should be instantiated before the header gets sent since it checks the form and if the Save/Create button is pressed,
 * it needs to redirect the page to another page. In that case, if the header is already sent, an error occurs.
 * 
 */
class Externals_Form_CategorySelect extends Externals_Form_CategorySelect_Base {

    /**
     * Stores the form options.
     * 
     * Identical to the options given by the below $oArgument object 
     * except this contains keys not defined in the unit default key structure.
     */
    public $aOptions;
    
    /**
     * Stores category unit options. 
     * 
     * Keys that are not defined in the default key structure will be stripped out.
     */
    public $oArgument;

    /**
     * User constructor.
     */
    public function construct( /* array $aExternalOptions=array(), array $aFormOptions=array() */ ) {
        
        $_aParams           = func_get_args() + array( 0 => array(), 1 => array() );
        $_aExternalOptions      = $_aParams[ 0 ];
        $_aFormOptions      = $_aParams[ 1 ];
     
        $this->aOptions     = array(
            'template_path' => Externals_Registry::$sDirPath . '/template/preview/template.php',
            'is_preview'    => true, // this disables the global ASIN blacklist.
        ) + $_aFormOptions;
        $this->oArgument  = new Externals_ExternalArgument_category(
            null,   // post id
            $this->aOptions
        );
        
    }
    
    /**
     * Renders the form fields for the category selection preview page.
     * 
     */
    public function render() {
        
        $sPageURL = $this->_getPageURL( 
            isset( $_GET['href'] ) 
                ? $_GET['href'] 
            : '', 
            $this->oArgument->get( 'country' )
        );
        $aSidebar = $this->_getSidebar( 
            $sPageURL, 
            $this->oArgument->get( 'country' )
        );
                            
        $this->_printPreviewTable(
            array(
                'bNew'                          => isset( $this->aOptions[ 'mode' ] ) 
                    ? $this->aOptions[ 'mode' ]
                    : 0, // 0 : edit, 1 : new
                'sPageURL'                      => $sPageURL,
                'sRSSURL'                       => $aSidebar['sRSSURL'],
                'aSelectedRSSURLs'              => $this->_getSelectedRSSURLs( $this->oArgument->get( 'categories' ) ),
                'sBounceURL'                    => $this->aOptions[ 'bounce_url' ],
                'aWorkingURLs'                  => array(),
                'sBreadcrumb'                   => $aSidebar['sRSSURL'] 
                    ? $aSidebar['sBreadcrumb'] 
                    : __( 'None', 'externals' ),
                'sSidebarHTML'                  => $aSidebar['sCategoryList'] 
                    ? $aSidebar['sCategoryList'] 
                    : $aSidebar['error'],
                'sSelectedCategories'           => $this->_getSelectedCategoryList( 
                    $this->oArgument->get( 'categories' ) 
                ),
                'sSelectedExcludingCategories'  => $this->_getSelectedCategoryList( 
                    $this->oArgument->get( 'categories_exclude' ) 
                ),
                'sSelectedPreview'              => '<p>' 
                        . __( 'Selected preview comes here.', 'amzon-auto-links' )
                    . '</p>',
                'sStoredPreview'                => '<p>'
                        . __( 'Stored preview comes here.', 'externals' )
                    . '</p>',
            )
        );
        
    }

        /**
         * A helper function for the above renderForm() method that retrieves the feed urls of added categories.
         * 
         */
        protected function _getSelectedRSSURLs( $aCategories ) {
                
            $aURLs = array();
            foreach( $aCategories as $aCategory ) {
                $aURLs[] = $aCategory['feed_url'];
            }
            return $aURLs;
            
        }    
        /**
         * 
         * $aCategory = array(
         *    'breadcrumb' => ... 
         *  'feed_url' => ..
         *  'page_url' => ...
         * );
         */
        protected function _getSelectedCategoryList( $aCategories ) {
            
            if ( $this->isEmpty( $aCategories ) ) {
                return "<p>" . __( 'No categories added.', ' externals' ) . "</p>"; 
            }
                
            $_aOutput = array();
            foreach( $aCategories as $sKey => $aCategory ) {
                
                $sName      = md5( $aCategory['feed_url'] );
                $sPageURL   = $this->formatLinkURL( $aCategory['page_url'] );
                $_aOutput[] = "<div class='category-select-selected-category'>" 
                        . "<label for='cb-{$sName}'>"
                            . "<input type='checkbox' name='externals_cat_select[checkboxes][{$sKey}]' value='{$sName}' id='cb-{$sName}' />"
                            . "<a href='{$sPageURL}'>{$aCategory['breadcrumb']}</a>"
                        . "</label>"
                    . "</div>";
                
            }
            
            return implode( PHP_EOL, $_aOutput );
        }
        
   
    /**
     * 
     * @uses        flush()
     */
    protected function _printPreviewTable( $aPageElements ) {
                        
        // Instantiate the core object - the fetching process should be done while rendering the HTML output
        // because it takes some time so the flush() function is used in the middle.
        $_oAALCatPreview   = new Externals_External_category( $this->oArgument );
        $_oAALExternalPreview  = new Externals_External_category( $this->oArgument );

        // Buttons 
        $bReachedLimit      = $this->isNumberOfCategoryReachedLimit(
            count( $this->oArgument->get( 'categories' ) ) 
            + count( $this->oArgument->get( 'categories_exclude' ) )
        );
        $bIsAlreadyAdded    = $this->isAddedCategory( 
            $aPageElements['sBreadcrumb'], 
            $this->oArgument->get( 'categories' ) 
        );
        $bIsAlreadyAddedExcludingCategory = $this->isAddedCategory( 
            $aPageElements['sBreadcrumb'], 
            $this->oArgument->get( 'categories_exclude' )
        );
        $bIsSubCategoryOfAddedItems = $this->isSubCategoryOfAddedItems( 
            $aPageElements['sBreadcrumb'], 
            $this->oArgument->get( 'categories' ) 
        );
        $sAddDisabled       = $this->isEmpty( $aPageElements['sRSSURL'] ) || $bIsAlreadyAdded || $bIsAlreadyAddedExcludingCategory 
            ? "disabled='disabled'" 
            : "";
        $sExcludeDisabled   = $this->isEmpty( $this->oArgument->get( 'categories' ) ) || $bIsAlreadyAdded || $bIsAlreadyAddedExcludingCategory || ! $bIsSubCategoryOfAddedItems 
            ? "disabled='disabled'" 
            : "";
        $sRemoveDisabled    = $this->isEmpty( $this->oArgument->get( 'categories' ) )
            ? "disabled='disabled'" 
            : "";
        $sCreateDisabled    = $this->isEmpty( $this->oArgument->get( 'categories' ) ) 
            ? "disabled='disabled'" 
            : "";
        $sCreateOrSave      = $aPageElements['bNew'] 
            ? __( 'Create', 'externals' ) 
            : __( 'Save', 'externals' );
        
        // Arrows
        $sAddArrow    = $aPageElements['bNew'] && ! $this->isEmpty( $aPageElements['sRSSURL'] ) && $this->isEmpty( $this->oArgument->get( 'categories' ) ) 
            ? "<img class='category-select-right-arrow' title='" . __( 'Add the current selection!', 'externals' ) . "' src='" . Externals_Registry::getPluginURL( 'asset/image/arrow_right.png' ) . "'/>" 
            : "";
        $sCreateArrow = $aPageElements['bNew'] && ! $this->isEmpty( $aPageElements['sRSSURL'] ) && ! $this->isEmpty( $this->oArgument->get( 'categories' ) ) 
            ? "<img class='category-select-right-arrow' title='" . __( 'Create the unit!', 'externals' ) . "' src='" . Externals_Registry::getPluginURL( 'asset/image/arrow_right.png' ) . "'/>" 
            : "";
        $sSelectArrow = $aPageElements['bNew'] && $this->isEmpty( $aPageElements['sRSSURL'] ) && $this->isEmpty( $this->oArgument->get( 'categories' ) ) 
            ? "<img class='category-select-left-bottom-arrow' title='" . __( 'Select a category from the links!', 'externals' ) . "' src='" . Externals_Registry::getPluginURL( 'asset/image/arrow_left_bottom.png' ) . "'/>" 
            : "";
        
        ?>

<input type="hidden" name="externals_cat_select[category][breadcrumb]" value="<?php echo $this->oEncrypt->encode( $aPageElements['sBreadcrumb'] ) ;?>" />
<input type="hidden" name="externals_cat_select[category][feed_url]" value="<?php echo $aPageElements['sRSSURL'] ;?>" />
<input type="hidden" name="externals_cat_select[category][page_url]" value="<?php echo $aPageElements['sPageURL'] ;?>" />
<table class="category-select-table">
    <tbody>
        <tr>
            <td class="category-select-first-column">                
                <h3><?php _e( 'Current Selection', 'externals' ); ?></h3>
                <p class="category-select-breadcrumb"><?php echo $aPageElements['sBreadcrumb']; ?></p>
            </td>
            <td class="category-select-second-column" colspan="2">        
                <div class="category-select-submit-buttons">
                    <span class="primary"><a class="button button-primary" href="<?php echo $aPageElements[ 'sBounceURL' ]; ?>"><?php _e( 'Go Back', 'externals' ); ?></a></span>
                    <span class="primary"><?php echo $sCreateArrow; ?><input type="submit" name="externals_cat_select[save]" class="button button-primary" value="<?php echo $sCreateOrSave; ?>" <?php echo $sCreateDisabled; ?> /></span>
                    <span><?php echo $sAddArrow; ?><input type="submit" name="externals_cat_select[add]" class="button button-secondary" value="<?php _e( 'Add Category', 'externals' ); ?>" <?php echo $sAddDisabled; ?> /></span>
                    <span><input type="submit" name="externals_cat_select[exclude]" class="button button-secondary" value="<?php _e( 'Add Excluding Category', 'externals' ); ?>" <?php echo $sExcludeDisabled; ?> /></span>                                
                    <span><input type="submit" name="externals_cat_select[remove]" class="button button-secondary" value="<?php _e( 'Remove Checked', 'externals' ); ?>" <?php echo $sRemoveDisabled; ?> /></span>
                </div>
                <div>
                    <h3><?php _e( 'Added Categories', 'externals' ); ?></h3>
                    <?php echo $aPageElements['sSelectedCategories']; ?>
                    <h3><?php _e( 'Added Excluding Sub-categories', 'externals' ); ?></h3>
                    <?php echo $aPageElements['sSelectedExcludingCategories']; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="category-select-first-column">
                <h3><?php _e( 'Select Category', 'externals' ); ?></h3>
                <?php echo $sSelectArrow; ?>
                <?php echo $aPageElements['sSidebarHTML']; ?>
            </td>
            <td class="category-select-second-column category-select-preview-left">
                <h3>
                    <?php echo $aPageElements['sRSSURL'] 
                        ? __( 'Preview of This Category', 'externals' ) 
                        : __( 'No Preview', 'externals' ); 
                    ?>
                </h3>                        
                <div class="widthfixer" style="width:<?php echo $this->oArgument->get( 'image_size' ); ?>px;"></div>
                <?php 
                if ( $aPageElements['sRSSURL'] ) {
                    $_oAALCatPreview->render( array( $aPageElements['sRSSURL'] ) );
                    // flush();
                } else 
                    _e( 'Please select a category from the list on the left.', 'externals' );  
                ?>
            </td>
            <td class="category-select-third-column category-select-preview-right">
                <h3><?php _e( 'External Preview', 'externals' ); ?></h3>                            
                <div class="widthfixer" style="width:<?php echo $this->oArgument->get( 'image_size' ); ?>px;"></div>
                <?php                         
                if ( ! $this->isEmpty( $aPageElements['aSelectedRSSURLs'] ) ) { 
                    $_oAALExternalPreview->render( $aPageElements['aSelectedRSSURLs'] );
                    flush(); 
                } else 
                    _e( 'Please add a category from the list after selecting it.', 'externals' );
                ?>
            </td>
        </tr>
    </tbody>
</table>    
            <?php
                
            // Debug info
            $_oOption = Externals_Option::getInstance();
            if ( $_oOption->isDebugMode() ) {
                echo "<h4>" . __( 'Debug Info', 'externals' ). "</h4>";
                echo "<h5>" . __( 'Page URL', 'externals' ) . "</h5>";
                Externals_Debug::dump( $aPageElements['sPageURL'] );
                echo "<h5>" . __( 'Feed URL', 'externals' ) . "</h5>";
                Externals_Debug::dump( $aPageElements['sRSSURL'] );
            }
            
        }
    
        /**
         * Determines whether the current browsing category is already added or not.
         */
        protected function isAddedCategory( $sBreadCrumb, $aCategories ) {
            
            foreach( $aCategories as $aCategory ) {
                if ( trim( $aCategory['breadcrumb'] ) == trim( $sBreadCrumb ) ) {
                    return true;
                }
            }
            return false;
            
        }
        /**
         * Determines whether the current browsing category is a sub-category of added ones.
         */
        protected function isSubCategoryOfAddedItems( $sBreadCrumb, $aCategories ) {
            
            foreach( $aCategories as $_aCategory ) {
                if ( 
                    ( false !== strpos( trim( $sBreadCrumb ), trim( $_aCategory[ 'breadcrumb' ] ) ) )
                    && ( trim( $_aCategory[ 'breadcrumb' ] ) != trim( $sBreadCrumb ) )
                ) {
                    return true;
                }
            }
            
        }

   
    /**
     * Returns the decrypted URL.
     * 
     * @since        1
     */
    protected function _getPageURL( $sEncryptedURL, $sLocale='US' ) {
                
        $_sURL = $sEncryptedURL 
            ? $this->oEncrypt->decode( $sEncryptedURL  )
            : ( isset( Externals_Property::$aCategoryRootURLs[ $sLocale ] ) 
                ? Externals_Property::$aCategoryRootURLs[ $sLocale ] 
                : Externals_Property::$aCategoryRootURLs[ 'US' ] 
            );
            
        // Add a trailing slash; this is tricky, the uk and ca sites have an issue that they display a not-found(404) page when the trailing slash is missing.
        // e.g. http://www.amazon.ca/Bestsellers-generic/zgbs won't open but http://www.amazon.ca/Bestsellers-generic/zgbs/ does.
        // Note that this problem has started occurring after using wp_remote_get(). So it has something to do with the function.             
        return trailingslashit( $_sURL ); 
        
    }
    
    /**
     * Represents the sidebar array.
     * 
     */
    protected static $aStructure_Sidebar = array(
        'sRSSURL'       => null,
        'sCategoryList' => null,
        'sBreadcrumb'   => null,
        'error'         => null,
    );    
    /**
     * Retrieves the sidebar category list.
     * 
     * @since        1
     * @remark           Due to missing elements with the DOMDocument class in some Japanese pages, 
     * this method uses the simple_html_dom library.
     */
    protected function _getSidebar( $sPageURL, $sLocale='US', $iAttempt=0 ) {
        
        // Include the library.
        if ( ! class_exists( 'simple_html_dom_node' ) ) {
            include_once( Externals_Registry::$sDirPath . '/include/library/simple_html_dom.php' );
        }

        // This has a caching functionality.
        $_oHTTP = new Externals_HTTPClient( $sPageURL );
        $sHTML  = $_oHTTP->get();        
        if ( ! $sHTML ) {
            return array( 
                    'error' => __( "Could not retrieve the category list: {$sPageURL}. Please consult the plugin developer.", 'externals' ) 
                ) 
                + self::$aStructure_Sidebar;
        }
     
        // Instantiate the class.
        $_oSimpleDOM = str_get_html( $sHTML );        
        if ( ! $_oSimpleDOM->find( "#zg_browseRoot", 0 ) ) {

            $_oHTTP->deleteCache();
      
            // Try with a R18 confirmation redirect - must use file_get_contents(), not wp_remote()
            if ( $iAttempt >= 2 ) {
                
                $_sRedirectURL = Externals_Property::$aCategoryBlackCurtainURLs[ $sLocale ] . '?redirect=true&redirectUrl=' . urlencode( $sPageURL );              
                $_oHTTP        = new Externals_HTTPClient_FileGetContents( $_sRedirectURL );
                $sHTML         = $_oHTTP->get();
                if ( ! $sHTML ) {
                    $_oHTTP->deleteCache();
                    return array( 
                            'error' => __( "Could not load the page for the second attempt: {$_sRedirectURL}. Please consult the plugin developer.", 'externals' )
                        ) + self::$aStructure_Sidebar;
                }
                
                $_oSimpleDOM = str_get_html( $sHTML );
                if ( ! $_oSimpleDOM->find( "#zg_browseRoot", 0 ) ) {
                    
                    $sHTML = $_oSimpleDOM->outertext;
                    $_oHTTP->deleteCache();
                    return array( 
                        'error' => sprintf( 
                            __( 'Could not find the category in this page: %1$s Please consult the plugin developer.', 'externals' ),
                            $_sRedirectURL
                        )                         
                    ) + self::$aStructure_Sidebar;
                }
                return array(
                    'sRSSURL'       => $this->_getCategoryFeedURL( $_oSimpleDOM ),    
                    // must be done after the _getCategoryFeedURL() method as this method modifies the links.
                    'sCategoryList' => $this->_getCategoryList( $_oSimpleDOM ), 
                    'sBreadcrumb'   => $this->_getBreadcrumb( $_oSimpleDOM, $sLocale ),
                );        
                
            }
            return $this->_getSidebar( $sPageURL, $sLocale, ++$iAttempt );
    
        }
        
        return array(
            'sRSSURL'       => $this->_getCategoryFeedURL( $_oSimpleDOM ),    
            'sCategoryList' => $this->_getCategoryList( $_oSimpleDOM ), 
            'sBreadcrumb'   => $this->_getBreadcrumb( $_oSimpleDOM, $sLocale ),
        );    
        
    }
        /**
         * 
         * @return  string
         */
        protected function getRedirectDestination( $sURL ) {
            
            $k = curl_init( $sURL );
            curl_setopt( $k, CURLOPT_FOLLOWLOCATION, true ); // follow redirects
            curl_setopt( $k, CURLOPT_USERAGENT, 
                'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.7 ' .
                '(KHTML, like Gecko) Chrome/7.0.517.41 Safari/534.7'
            ); // imitate chrome
            curl_setopt( $k, CURLOPT_NOBODY, true ); // HEAD request only (faster)
            curl_setopt( $k, CURLOPT_RETURNTRANSFER, true ); // don't echo results
            curl_exec($k);
            $_sFinalURL = curl_getinfo( $k, CURLINFO_EFFECTIVE_URL ); // get last URL followed
            curl_close($k);
            return $_sFinalURL;            
            
        }
    
    /**
     * Returns the language code of the specified Amazon store locale.
     * 
     * Either ja, en, or uni is returned.
     * 
     */
    protected function getMBLanguage( $sLocale='US' ) {
        return isset( Externals_Property::$aCategoryPageMBLanguages[ $sLocale ] ) 
                ? Externals_Property::$aCategoryPageMBLanguages[ $sLocale ] 
                : 'uni';        
    }
    
    /**
     * Generates the HTML output of the node tree list.
     * 
     * @since        1
     */
    protected function _getCategoryList( $_oSimpleDOM ) {
        
        $_oNodeBrowseRoot = $_oSimpleDOM->getElementById( 'zg_browseRoot' );
        $this->modifyHref( $_oNodeBrowseRoot );
        return $_oNodeBrowseRoot->outertext; // the sidebar html code
        
    }    
        
    private function removeLineFeeds( $sOutput ) {
                
        $sOutput    = str_replace( array( "\r\n", "\r" ), "\n", $sOutput );
        
        $aLines     = explode( "\n", $sOutput );
        $aNewLines  = array();
        foreach( $aLines as $i => $sLine ) {
            if( ! $this->isEmpty( $sLine ) ) {
                $aNewLines[] = trim( $sLine, '\t\n\r\0\x0B' );
            }
        }
        
        return implode( $aNewLines );
    
    }
    /**
     * Converts href urls io a url with query which contains the original url.
     * 
     * e.g. <a href="http://amazon.com/something"> -> <a href="localhost/me.php?href=http://amazon.com/something"
     * and the href value beceomes base64 encoded.
     */
    protected function modifyHref( $_oSimpleDOMNode, $aQueries=array() ) {
        
        foreach( $_oSimpleDOMNode->getElementsByTagName( 'a' ) as $nodeA ) {
            
            $sHref = $nodeA->getAttribute( 'href' );
            
            // sip the sing after 'ref=' in the url
            // e.g. http://amazon.com/ref=zg_bs_123/324-5242552 -> http://amazon.com
            $aURL  = explode( "ref=", $sHref, 2 );
            $sHref = $aURL[0];            
            
            $nodeA->setAttribute( 'href', $this->formatLinkURL( $sHref, $aQueries ) );
            
        }
        
    }
    protected function _modifyHref( $oDOM, $aQueries=array() ) {    
            
        $aQueries   = ( array ) $aQueries;
        $oXpath     = new DOMXPath( $oDOM );     // since getElementByID constantly returned false for unknown reason, use xpath
        $domleftCol = $oXpath->query( "//*[@id='zg_browseRoot']" )->item( 0 );        // $domleftCol = $oDOM->getElementById('zg_browseRoot');
        if ( !$domleftCol ) {
            echo '<!-- ' . __( 'Categories not found. Please consult the plugin developer.', 'externals' ) . ' -->' . PHP_EOL;
            return false;
        }
        foreach( $oDOM->getElementsByTagName( 'a' ) as $nodeA ) {
            
            $sHref = $nodeA->getAttribute( 'href' );
            $nodeA->removeAttribute( 'href' );
            
            // sip the sing after 'ref=' in the url
            // e.g. http://amazon.com/ref=zg_bs_123/324-5242552 -> http://amazon.com
            $aURL  = explode( "ref=", $sHref, 2 );
            $sHref = $aURL[0];
            
            @$nodeA->setAttribute( 'href', $this->formatLinkURL( $sHref, $aQueries ) );
            
        }    
        return true;
        
    }    
    
    /**
     * Gets the current self-url. needs to exclude the query part 
     * e.g. http://localhost/me.php?href=http://....  -> http://localhost/me.php
     * @return       string
     */
    protected function formatLinkURL( $sURL, $aQueries=array() ) {
        return add_query_arg( 
            array( 
                'href' => $this->oEncrypt->encode( $sURL ),
            ) + $aQueries + $_GET                
            , admin_url( $GLOBALS['pagenow'] ) 
        );
    }
    
    /**
     * Creates a breadcrumb of the Amazon page sidebar.
     * 
     * This is specific to Amazon's store page so if the site page sucture changes, it won't work.
     * Especially it uses the unique id and class names including zg_browseRoot, zg_selected, the sidebar element IDs. 
     * 
     * @since        1
     */
    protected function _getBreadcrumb( $_oSimpleDOM, $sLocale='US' ) {
        
        $aBreadcrumb    = array();
        $nodeBrowseRoot = $_oSimpleDOM->getElementById( 'zg_browseRoot' );
        $nodeSelected   = $nodeBrowseRoot->find( '.zg_selected', 0 );
        if ( ! $nodeSelected ) {
            return __( 'Failed to generate the breadcrumb.', 'externals' );
        }
            
        // Current category
        $aBreadcrumb[]  = trim( $nodeSelected->plaintext );        
        
        // Climb up the node
        $nodeClimb      = $nodeSelected->parentNode();
        Do {
            if ( $nodeClimb->nodeName() == 'ul' ) {
                $nodeUpperUl   = $nodeClimb->parentNode();
                $nodeLi        = $nodeUpperUl->getElementByTagName( 'li' );
                $nodeA         = $nodeLi->getElementByTagName( 'a' );
                $aBreadcrumb[] = trim( $nodeA->innertext );
            }
            $nodeClimb = $nodeClimb->parentNode();    
            
        } While ( $nodeClimb && $nodeClimb->getAttribute( 'id' ) != 'zg_browseRoot' );

        array_pop( $aBreadcrumb );    // remove the last element
        $aBreadcrumb[] = strtoupper( $sLocale );    // set the last element to the country code
        $aBreadcrumb   = array_reverse( $aBreadcrumb );
        return implode( " > ", $aBreadcrumb );
        
    }
            
    /**
     * Extracts the category feed url from the given DOM object.
     * 
     * @since        1
     */
    protected function _getCategoryFeedURL( $_oSimpleDOM ) {
        
        $domRSSLinks = $_oSimpleDOM->getElementById( 'zg_rssLinks' );
        if ( ! $domRSSLinks ) {
            
            // the root category does not provide a rss link, so return silently
            echo '<!-- ' . __METHOD__ . ': The zg_rssLinks ID element could not be found. -->';
            return;
            
        }
        
        $nodeA2     = $domRSSLinks->getElementsByTagName( 'a', 1 ); // the second link.
        $sRSSLink   = $nodeA2->getAttribute( 'href' );
        $aURL       = explode( "ref=", $sRSSLink, 2 );
        return $aURL[ 0 ];
    
    }

}
        