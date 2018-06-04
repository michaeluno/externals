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
 * Adds the 'Add New External' tab to the 'Add New External' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_AddNewExternalAdminPage_TypeSelect_Select extends Externals_AdminPage_Tab_Base {
    
    public function construct( $oFactory ) {}
    
    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadTab( $oAdminPage ) {
        
        // Form sections
        new Externals_AddNewExternalAdminPage_TypeSelect_Select_Type( 
            $oAdminPage,
            $this->sPageSlug, 
            array(
                'tab_slug'      => $this->sTabSlug,
                'section_id'    => '_default', 
                'title'         => __( 'External Types', 'externals' ),
            )
        );           
         
        
    }
    
    /**
     * 
     * @callback        filter      validation_{page slug}_{tab slug}
     */
    public function validate( $aInput, $aOldInput, $oFactory, $aSubmitInfo ) {

        $_bVerified = ! $oFactory->hasFieldError();
        $_aErrors   = array();
        $_oOption   = Externals_Option::getInstance();
    
        // Check the limitation.
        if ( $_oOption->isExternalLimitReached() ) {

            // must set an field error array which does not yield empty so that it won't be redirected.
            $oFactory->setFieldErrors( array( 'error' ) );        
            $oFactory->setSettingNotice( 
                sprintf( 
                    __( 'Please upgrade to <A href="%1$s">Pro</a> to add more units! Make sure to empty the <a href="%2$s">trash box</a> to delete the units completely!', 'externals' ), 
                    'http://en.michaeluno.jp/externals-pro/',
                    admin_url( 'edit.php?post_status=trash&post_type=' . Externals_Registry::$aPostTypes[ 'external' ] )
                )
            );
            return $aOldInput;
            
        }   

        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oFactory->setFieldErrors( $_aErrors );     
            $oFactory->setSettingNotice( __( 'There was something wrong with your input.', 'externals' ) );
            return $aInput;
        }        
             
        // $aInput[ 'transient_id' ] = isset( $_REQUEST[ 'transient_id' ] )
            // ? $_REQUEST[ 'transient_id' ]
            // : Externals_Registry::TRANSIENT_PREFIX . '_CreateExternal_' . uniqid();
        $aInput[ 'bounce_url' ]   = add_query_arg(
            array(  
                'transient_id'  => $aInput[ 'transient_id' ],
                'externals_action'    => 'select_category',
                'page'          => $this->sPageSlug, // Externals_Registry::$aAdminPages[ 'category_select' ],
                'tab'           => $this->sTabSlug, // 'first'
            ) 
            + $_GET,
            admin_url( $GLOBALS[ 'pagenow' ] )
        );        
        $oFactory->setSettingNotice( 
            __( 'Select a category from the below list.', 'externals' ),
            'updated'
         );
        
        return $aInput;
        
    }
    
    
        /**
         * 
         * @remark      Will redirect the user to the next page and exits the script.
         */
        private function _goToNextPage( $aInput ) {
                
            // Format the submitted data.
            $aInput[ 'transient_id' ] = isset( $_GET[ 'transient_id' ] )
                ? $_GET[ 'transient_id' ]
                : uniqid();
            $aInput[ 'bounce_url' ]   = add_query_arg(
                array(  
                    'transient_id'  => $aInput[ 'transient_id' ],
                    'externals_action'    => 'select_category',
                    'page'          => $this->sPageSlug, // Externals_Registry::$aAdminPages[ 'category_select' ],
                    'tab'           => $this->sTabSlug, // 'first'
                ) 
                + $_GET,
                admin_url( $GLOBALS[ 'pagenow' ] )
            );
return $aInput;
            // Save the data in a temporary area of the databse.
            $_bSucceed = Externals_WPUtility::setTransient(
                Externals_Registry::TRANSIENT_PREFIX . '_CreateExternal_' . $aInput[ 'transient_id' ],
                $aInput,
                60*10*6*24 
            );
    

            // Go to the next page.
            exit( 
                wp_safe_redirect(
                    add_query_arg(
                        array(
                            'transient_id'  => $aInput[ 'transient_id' ],
                            'externals_action'    => 'select_category',
                            'tab'           => 'second',
                        )
                        + $_GET,
                        admin_url( $GLOBALS[ 'pagenow' ] )
                    )
                )
            );
        }    
    
}
