<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates plugin specific database tables.
 * 
 * @since       1
 */
class Externals_DatabaseTable_externals_request_cache extends Externals_DatabaseTable_Utility {

    /**
     * Returns the table arguments.
     * @return      array
     * @since       0.3.13
     */
    protected function _getArguments() {
        return Externals_Registry::$aDatabaseTables[ 'externals_request_cache' ];
    }

    /**
     * 
     * @return      string
     * @since       1
     */
    public function getCreationQuery() {

        $_sCharset = $GLOBALS[ 'wpdb' ]->get_charset_collate();
        
        // With some unicode collation including utf8mb4_unicode_ci, the `varchar` type cannot be set to more than 191 
        // @see http://stackoverflow.com/questions/1814532/1071-specified-key-was-too-long-max-key-length-is-767-bytes/31474509#31474509
        return "CREATE TABLE " . $this->getTableName() . " (
            name varchar(191) UNIQUE,    
            request_uri text,   
            type varchar(20),
            charset varchar(20),
            cache longblob,
            modified_time datetime NOT NULL default '0000-00-00 00:00:00',
            expiration_time datetime NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY  (name)
        ) " . $_sCharset . ";";
        
    }
    
    /**
     * 
     * @return  boolean     Whether it is set or not.
     */
    public function setCache( $sName, $mData, $iDuration=0, array $aExtra=array() ) {
        
        $_aColumns = array(
            'name'              => '',
            'request_uri'       => '',
            'type'              => '',
            'charset'           => '',
            'cache'             => '',
            'modified_time'     => '0000-00-00 00:00:00',
            'expiration_time'   => '0000-00-00 00:00:00',
        );
         
        return $this->setRow( 
            array(
                'name'  => $sName,
                'cache' => maybe_serialize( $mData ),
            ) 
            + array_intersect_key( $aExtra, $_aColumns ) // removes unsupported items
            + array(
                'modified_time'   => date( 'Y-m-d H:i:s' ), 
                'expiration_time' => date( 'Y-m-d H:i:s', time() + $iDuration ),
            )
        );
        
    }
        /**
         * Sets a row.
         * @return      boolean     Whether it is set or not.
         */
        public function setRow( $aRow ) {
            
            if ( ! isset( $aRow[ 'name' ] ) ) {
                return false;
            }
         
            if ( $this->doesRowExist( $aRow[ 'name' ] ) ) {
                $_iCountSetRows = $this->update( 
                    $aRow, // data
                    array( // where
                        'name' => $aRow[ 'name' ],
                    )
                );
            } else {
                $_iCountSetRows = $this->replace( $aRow );
            }     
            return $_iCountSetRows
                ? true
                : false;
                
        }    
            /**
             * Checks whether the given cache exists or not by the given name.
             * @return      boolean
             */
            public function doesRowExist( $sName ) {
                return ( boolean ) $this->getVariable(
                    "SELECT name
                    FROM {$this->aArguments[ 'table_name' ]}
                    WHERE name = '{$sName}'"
                );             
            }
    
    /**
     * 
     * @return      array
     * The structure 
     * array(
     *  'remained_time' => (integer)
     *  'data'          => (mixed)
     * )
     */
    public function getCache( $asNames, $iCacheDuration=null ) {
        return is_array( $asNames )
            ? $this->_getMultipleRows( $asNames, $iCacheDuration )
            : $this->_getCacheEach( $asNames, $iCacheDuration );
    }
        /**
         * 
         * @remark      Saves the number of MySQL queries by passing multiple items at once.
         * @return      array
         */
        private function _getMultipleRows( $aNames, $iCacheDuration=null ) {

            $_sNames   = "('" . implode( "','", $aNames ) . "')";
            $_aResults =  $this->getRows(
                "SELECT cache,modified_time,expiration_time,charset,request_uri,name
                FROM {$this->aArguments[ 'table_name' ]}
                WHERE name in {$_sNames}"
            );       

            $_aRows = array();
            foreach( $_aResults as $_aResult ) {

                if ( ! is_array( $_aResult ) ) {
                    continue;
                }
                $_aRows[ $_aResult[ 'name' ] ] = $this->_getFormattedRow( $_aResult, $iCacheDuration );
            }
            return $_aRows;
            
        }
        /**
         * 
         * @return      array
         */
        private function _getCacheEach( $sName, $iCacheDuration=null ) {
            
            $_aRow = $this->getRow(
                "SELECT cache,modified_time,expiration_time,charset,request_uri,name
                FROM {$this->aArguments[ 'table_name' ]}
                WHERE name = '{$sName}'",
                'ARRAY_A' 
            );                
            return $this->_getFormattedRow( $_aRow, $iCacheDuration );

        }    
            /**
             * @param       array       $aRow               The row array returned from the database.
             * @param       integer     $iCacheDuraiton     The cache duration in seconds. If not set, the stored cache duration will be used.
             * @return      array
             */
            private function _getFormattedRow( $aRow, $iCacheDuration=null ) {
                $_aRow = is_array( $aRow )
                    ? array(
                        'remained_time' => null !== $iCacheDuration
                            ? strtotime( $aRow[ 'modified_time' ] ) + $iCacheDuration - time()
                            : strtotime( $aRow[ 'expiration_time' ] ) - time(),                        
                        'charset'       => $aRow[ 'charset' ],
                        'data'          => maybe_unserialize( $aRow[ 'cache' ] ), 
                    ) + $aRow
                    : array();
                unset( $_aRow[ 'cache' ] );
                return $_aRow + array(
                    'remained_time' => 0,
                    'charset'       => '',
                    'data'          => null,
                    'name'          => '',
                    
                    // These are for debugging
                    '_cache_duration'        => $iCacheDuration,
                    '_now'                   => time(),
                    '_modified_timestamp'    => strtotime( $aRow[ 'modified_time' ] ),
                    '_expiration_timestamp'  => strtotime( $aRow[ 'expiration_time' ] ),
                    
                );
            }     
            
    
    /**
     * Deletes the cache(s) by given cache name(s).
     */
    public function deleteCache( $asNames='' ) {
                
        $_aNames = is_array( $asNames )
            ? $asNames
            : array( 0 => $asNames );
        foreach( $_aNames as $_sName ) {            
            $this->delete(
                array(
                    'name' => $_sName,
                )
            );
        }
        
    }
    

    
}