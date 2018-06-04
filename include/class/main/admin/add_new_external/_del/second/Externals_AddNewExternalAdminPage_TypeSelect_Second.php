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
 * Adds the 'Select Categories' tab to the 'Add New External' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_AddNewExternalAdminPage_TypeSelect_Second extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oFactory ) {
                
        // Create a dummy form to trigger a validation callback.
        $oFactory->addSettingFields(
            '_default', // the target section id    
            array(
                'field_id'      => '_dummy_field_for_validation',
                'hidden'        => true,
                'type'          => 'hidden',
                'value'         => $GLOBALS[ 'externals_transient_id' ],
                'attributes'    => array(
                    'name'  => 'transient_id',
                ),
            )
        );
        
        // Load the Preview template CSS file.
        $oFactory->enqueueStyle( Externals_Registry::getPluginURL( 'template/preview/style-preview.css' ) );
        
    }
            
    public function replyToDoTab( $oFactory ) {

        // The object handles the form validations and redirection.
        // So this needs to be done in the load_{...} callback.
        // Then in the do_{...} callback, the form will be rendered.        
        $_oCategorySelectForm = new Externals_Form_CategorySelect(
            $oFactory->oProp->aOptions,     // unit options
            $oFactory->oProp->aOptions      // form options
        );            
        $_oCategorySelectForm->render();
        
        $this->_printDebugInfo( $oFactory );
        
    }
        /**
         * Debug information
         * @since       1
         * @return      void
         */
        private function _printDebugInfo( $oFactory ) {
                    
            $_oOption = Externals_Option::getInstance();
            if ( ! $_oOption->isDebugMode() ) {
                return;
            }                
            echo "<h3>"     
                    . __( 'Form Options', 'externals' ) 
                . "</h3>";
            $oFactory->oDebug->dump(
                $oFactory->getValue()
            );
            
        }
    
    /**
     * 
     * @callback        filter      validation_{page slug}_{tab slug}
     */
    public function validate( $aInput, $aOldInput, $oFactory, $aSubmitInfo ) {

        $_bVerified = ! $oFactory->hasFieldError();
        
        // Disable the setting notice in the next page load.
        $oFactory->setSettingNotice( '' );        
               
        // If the user presses one of the custom form submit buttons, 
        if ( isset( $_POST[ 'externals_cat_select' ] ) ) {
            return $this->_getCategorySelectFormInput(
                $_POST[ 'externals_cat_select' ],
                $aOldInput,
                $oFactory
            );
        }   
        
        // $aInput just contains dummy items so do not use it.
        return $aOldInput;
        
    }    
    
        /**
         * Called when the user submits the form of the category select page.
         * @return      array
         */
        private function _getCategorySelectFormInput( array $aPost, $aInput, $oFactory ) {
                        
            // If the 'Save' or 'Create' button is pressed, save the data to the post.
            // The key is set in `Externals_Form_CategorySelect` when rendering the form.
            if ( isset( $aPost[ 'save' ] ) ) {
                $_oExternal = new Externals_ExternalArgument_category(
                    isset( $_GET[ 'post' ] )
                        ? $_GET[ 'post' ]
                        : null,
                    $aInput // unit options
                );
                $_iPostID = $this->_postExternalByCategory( 
                    $_oExternal->get(), // sanitized
                    $aInput // not sanitized, contains keys not needed for the unit options
                );
                if ( $_iPostID ) {
                    $oFactory->setSettingNotice( 
                        __( 'A unit has been created.', 'amazon-auto=links' ),
                        'updated'
                    );
                }
                
                $_oUtil = new Externals_PluginUtility;
                
                // Clean temporary options.
                $_oUtil->deleteTransient(
                    $GLOBALS[ 'externals_transient_id' ]
                );
                
                // Schedule pre-fetch.
                Externals_Event_Scheduler::prefetch( $_iPostID );
                
                // Will exit the script.
                $_oUtil->goToPostDefinitionPage(
                    $_iPostID,
                    Externals_Registry::$aPostTypes[ 'external' ]
                );
            }  
                                
            // Otherwise, update the form data.
            return $this->_getUpdatedExternalOptions(
                $aPost,
                $aInput,
                $oFactory
            );
            
        }
 
        /**
         * Creates a post of externals custom post type with unit option meta fields.
         * 
         * @return      integer     the post(unit) id.
         */
        private function _postExternalByCategory( $aExternalOptions, $aOptions ) {
            
            $_iPostID = 0;
            
            // Create a custom post if it's a new unit.
            if ( ! isset( $_GET['post'] ) || ! $_GET['post'] ) {
                $_iPostID = wp_insert_post(
                    array(
                        'comment_status'    => 'closed',
                        'ping_status'       => 'closed',
                        'post_author'       => $GLOBALS[ 'user_ID' ],
                        'post_title'        => $aOptions[ 'external_title' ],
                        'post_status'       => 'publish',
                        'post_type'         => Externals_Registry::$aPostTypes[ 'external' ],
                    )
                );        
            }
            
            // Add meta fields.
            $_iPostID = 1 == $aOptions[ 'mode' ]
                ? $_iPostID 
                : $_GET[ 'post' ];
            
            // Remove unnecessary items.
            // The unit title was converted to post_title above.
            unset( 
                $aExternalOptions[ 'external_title' ],
                $aExternalOptions[ 'is_preview' ] 
            );

            $_oOption = Externals_Option::getInstance();
            $_oTemplateOption   = Externals_TemplateOption::getInstance();
            $aExternalOptions[ 'template_id' ] = $_oTemplateOption->getDefaultTemplateIDByExternalType( 
                'category'
            );
            Externals_WPUtility::updatePostMeta( $_iPostID, $aExternalOptions );

            // Create an auto insert - the 'auto_insert' key will be removed when creating a post.s
            if ( 
                isset( $aOptions[ 'auto_insert' ] ) 
                && $aOptions[ 'auto_insert' ] 
                && 1 == $aOptions[ 'mode' ]  // new
            ) {
                Externals_PluginUtility::createAutoInsert( 
                    $_iPostID 
                );
            }    
            
            return $_iPostID;
            
        }            
        
    
   
    /**
     * Processes the user submitted form data in the Category Select page.
     * 
     * @return      array      The (un)updated data.
     */
    private function _getUpdatedExternalOptions( $aPost, array $aInput, $oFactory ) {
            
        $_iNumberOfCategories = count( $aInput[ 'categories' ] )  
            + count( $aInput[ 'categories_exclude' ] );
        
        // Check the limit
        if ( 
            ( isset( $aPost['add'] ) || isset( $aPost['exclude'] ) ) 
            && $this->_isNumberOfCategoryReachedLimit( $_iNumberOfCategories ) 
        ) {
            $oFactory->setSettingNotice(
                $this->_getLimitNotice( true ) 
            );
            return $aInput;
        }

        /*
         * Structure of the category array
         * 
         * md5( $aCurrentCategory['feed_url'] ) => array(
         *         'breadcrumb' => 'US > Books',
         *         'feed_url' => 'http://amazon....',    // the feed url of the category
         *         'page_url' => 'http://...'        // the page url of the category
         * 
         * );
         */
        $_oEncrypt              = new Externals_Encrypt;        
        $aCategories            = $aInput[ 'categories' ];
        $aExcludingCategories   = $aInput[ 'categories_exclude' ];
        $aCurrentCategory       = $aPost[ 'category' ];
        $aCurrentCategory[ 'breadcrumb' ] = $_oEncrypt->decode( 
            $aCurrentCategory[ 'breadcrumb' ] 
        );
                        
        // Check if the "Add Category" button is pressed
        if ( isset( $aPost['add'] ) ) {
            $aCategories[ md5( $aCurrentCategory['feed_url'] ) ] =  $aCurrentCategory;
        }
        
        if ( isset( $aPost['exclude'] ) ) {
            $aExcludingCategories[ md5( $aCurrentCategory['feed_url'] ) ] = $aCurrentCategory;
        }
        
        // Check if the "Remove Checked" button is pressed
        if ( isset( $aPost['remove'], $aPost[ 'checkboxes' ] ) ) {
            foreach( $aPost[ 'checkboxes' ] as $_sKey => $_sName ) {
                unset( $aCategories[ $_sName ] );
                unset( $aExcludingCategories[ $_sName ] );
            }
        }
                    
        $aInput['categories']         = $aCategories;
        $aInput['categories_exclude'] = $aExcludingCategories;
        return $aInput;
        
    }
        /**
         * Checks whether the category item limit is reached.
         * 
         */
        private function _isNumberOfCategoryReachedLimit( $iNumberOfCategories ) {
            $_oOption = Externals_Option::getInstance();
            return ( boolean ) $_oOption->isReachedCategoryLimit( 
                $iNumberOfCategories
            );            
        }           
    
        /**
         * Returns the admin message.
         * @return      string
         */
        private function _getLimitNotice( $bIsReachedLimit, $bEnableHTMLTag=true ) {
            if ( ! $bIsReachedLimit ) {
                return '';
            }
            return $bEnableHTMLTag 
                ? sprintf( __( 'Please upgrade to <a href="%1$s" target="_black">Pro</a> to add more categories.', 'externals' ), 'http://en.michaeluno.jp/externals-pro/' )
                : __( 'Please upgrade to Pro to add more categories!', 'externals' );
        }         

    
}