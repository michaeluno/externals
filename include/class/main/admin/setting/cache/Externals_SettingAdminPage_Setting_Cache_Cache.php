<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 */

/**
 * Adds the 'Cache' form section to the 'Cache' tab.
 * 
 * @since       1
 */
class Externals_SettingAdminPage_Setting_Cache_Cache extends Externals_AdminPage_Section_Base {
    
    /**
     * A user constructor.
     * 
     * @since       1
     * @return      void
     */
    protected function construct( $oFactory ) {}
    
    /**
     * Adds form fields.
     * @since       1
     * @return      void
     */
    public function addFields( $oFactory, $sSectionID ) {
                        
        $_oCacheTable      = new Externals_DatabaseTable_request_cache(
            Externals_Registry::$aDatabaseTables[ 'request_cache' ]
        );        
        $_iRequestCount    = $_oCacheTable->getVariable( 
            "SELECT COUNT(*) FROM {$_oCacheTable->sTableName}"
        );
        $_iExpiredRequests = $_oCacheTable->getVariable( 
            "SELECT COUNT(*) FROM {$_oCacheTable->sTableName} "
            . "WHERE expiration_time < NOW()" 
        );
        
        $oFactory->addSettingFields(
            $sSectionID, // the target section id    
            array( 
                'field_id'        => 'submit_clear_all_caches',
                'type'            => 'submit',
                'title'           => __( 'Clear All Caches', 'adazon-auto-links' ),
                'label_min_width' => 0,
                'label'           => __( 'Clear', 'externals' ),
                'attributes'      => array(
                    'class' => 'button button-secondary',
                ),
                'before_fieldset' => ''
                    . "<p style='margin-bottom: 1em;'>" 
                        . '<strong>' . __( 'Requests', 'externals' ) . '</strong>: ' . $_iRequestCount 
                    . '</p>',
            ),            
            array( 
                'field_id'        => 'submit_clear_expired_caches',
                'type'            => 'submit',
                'title'           => __( 'Clear Expired Caches', 'adazon-auto-links' ),
                'label_min_width' => 0,
                'label'           => __( 'Clear', 'externals' ),
                'attributes'      => array(
                    'class' => 'button button-secondary',
                ),
                'before_fieldset' => ''
                    . "<p style='margin-bottom: 1em;'>" 
                        . '<strong>' . __( 'Requests', 'externals' ) . '</strong>: ' . $_iExpiredRequests 
                    . '</p>',                
            ),
            array(
                'field_id'          => 'chaching_mode',
                'type'              => 'radio',
                'title'             => __( 'Caching Mode', 'externals' ),
                'capability'        => 'manage_options',
                'label' => array(
                    'normal'        => __( 'Normal', 'externals' ) . ' - ' . __( 'relies on WP Cron.', 'externals' ) . '<br />',
                    'intense'       => __( 'Intense', 'externals' ) . ' - ' . __( 'relies on the plugin caching method.', 'externals' ) . '<br />',
                ),
                'description'       => __( 'The intense mode should only be enabled when the normal mode does not work.', 'externals' ),
                'default' => 'normal',
            )            
        );    
    }
        
    
    /**
     * Validates the submitted form data.
     * 
     * @since       1
     */
    public function validate( $aInput, $aOldInput, $oAdminPage, $aSubmitInfo ) {
    
        $_bVerified = true;
        $_aErrors   = array();
        
        if ( 'submit_clear_all_caches' === $aSubmitInfo[ 'field_id' ] ) {
            $this->_clearAllCaches( $oAdminPage );
            return $aOldInput;            
        }
        if ( 'submit_clear_expired_caches' === $aSubmitInfo[ 'field_id' ] ) {
            $this->_clearExpiredCaches( $oAdminPage );
            return $aOldInput;
        }
                

        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oAdminPage->setFieldErrors( $_aErrors );     
            $oAdminPage->setSettingNotice( __( 'There was something wrong with your input.', 'externals' ) );
            return $aOldInput;
        }
                
        return $aInput;     
        
    }
    
        /**
         * Clears all the plugin caches.
         * @since       1
         * @return      void
         */
        private function _clearAllCaches( $oFactory ) {
            
            // Clear transients.
            Externals_WPUtility::cleanTransients( 
                Externals_Registry::TRANSIENT_PREFIX
            );
            Externals_WPUtility::cleanTransients( 
                'apf_'
            );            
            
            $_oCacheTable = new Externals_DatabaseTable_request_cache(
                Externals_Registry::$aDatabaseTables[ 'request_cache' ]
            );           
            $_oCacheTable->delete(
                // delete all rows by passing nothing.
            );    
            
            $oFactory->setSettingNotice( __( 'Caches have been cleared.', 'externals' ) );
            
        }
        /**
         * Clears expired caches.
         * @since       1
         * @return      void
         */
        private function _clearExpiredCaches( $oFactory ) {

            $_oCacheTable   = new Externals_DatabaseTable_request_cache(
                Externals_Registry::$aDatabaseTables[ 'request_cache' ]
            );           
            $_oCacheTable->deleteExpired();
            
            // DELETE FROM table WHERE (col1,col2) IN ((1,2),(3,4),(5,6))
            $oFactory->setSettingNotice( __( 'Caches have been cleared.', 'externals' ) );
        }   
}