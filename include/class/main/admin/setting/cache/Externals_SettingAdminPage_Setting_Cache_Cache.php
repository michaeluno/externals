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
                        
        $_oCacheTable      = new Externals_DatabaseTable_externals_request_cache;
        $_sTableName       = $_oCacheTable->getTableName();
        $_iRequestCount    = $_oCacheTable->getTotalItemCount();
        $_iExpiredRequests = $_oCacheTable->getExpiredItemCount();
        
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
                'before_fieldset' => "<p style='margin-bottom: 1em;'>"
                        . sprintf( __( '%1$s items', 'externals' ), $_iRequestCount )
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
                'before_fieldset' => "<p style='margin-bottom: 1em;'>"
                        . sprintf( __( '%1$s items', 'externals' ), $_iExpiredRequests )
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
            ),
            array(
                'field_id'       => 'clear_interval',
                'title'          => __( 'Clearing Cache Interval', 'fetch-tweets' ),
                'type'           => 'size',
                'units'          => array(
                    3600      => __( 'hour(s)', 'fetch-tweets' ),
                    86400     => __( 'day(s)', 'fetch-tweets' ),
                    604800    => __( 'week(s)', 'fetch-tweets' ),
                ),
                'description'    => __( 'An interval to clear expired caches.', 'fetch-tweets' ),
                'default'        => array(
                    'size'     => 7,
                    'unit'     => 86400,
                ),
                'attributes'   => array(
                    'size'  => array(
                        'min'   => 1
                    ),
                ),
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
            $this->___clearAllCaches( $oAdminPage );
            return $aOldInput;            
        }
        if ( 'submit_clear_expired_caches' === $aSubmitInfo[ 'field_id' ] ) {
            $this->___clearExpiredCaches( $oAdminPage );
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
        private function ___clearAllCaches( $oFactory ) {
            
            // Clear transients.
            Externals_WPUtility::cleanTransients( 
                Externals_Registry::TRANSIENT_PREFIX
            );
            Externals_WPUtility::cleanTransients( 
                'apf_'
            );            
            
            $_oCacheTable = new Externals_DatabaseTable_externals_request_cache;
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
        private function ___clearExpiredCaches( $oFactory ) {

            $_oCacheTable   = new Externals_DatabaseTable_externals_request_cache;
            $_oCacheTable->deleteExpired();
            $oFactory->setSettingNotice( __( 'Caches have been cleared.', 'externals' ) );

        }

}