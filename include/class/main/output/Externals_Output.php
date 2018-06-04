<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Handles unit outputs.
 * 
 * @package     Externals
 * @since       2
 * @since       1       Changed the name from `Externals_Externals`
*/
class Externals_Output extends Externals_PluginUtility {
    
    /**
     * Stores unit arguments.
     */
    public $aArguments = array();
    
    /**
     * Instantiates the class and returns the object.
     * 
     * This is to enable a technique to call a method in one line like
     * <code>
     * $sOutput = Externals_Externals::getInstance()->get();
     * </code>
     * 
     * @sicne       1
     */
    static public function getInstance( $aArguments ) {
        return new self( $aArguments );
    }

    /**
     * Sets up properties.
     * 
     * @since        1
     */
    function __construct( $aArguments ) {
        $this->aArguments = $aArguments;
    }
    
    /**
     * Retrieves the output.
     * @return      string
     */
    public function get() {

        // Retrieve IDs 
        $_aIDs     = $this->_getIDs();
        $_aOutputs = array();

        // If called by unit,
        if ( ! empty( $_aIDs ) ) {
            foreach( $_aIDs as $_iID ) {            
                $_aOutputs[] = $this->_getOutputByID( $_iID );
            }
        } 
        // there are cases called without a unit 
        else {
            $_aOutputs[] = $this->_getOutputByExternalType( 
                apply_filters( 
                    Externals_Registry::HOOK_SLUG . '_filter_external_type_by_arguments', 
                    'text', 
                    $this->aArguments 
                ), 
                $this->aArguments
            );
        }
    
        return trim( implode( '', $_aOutputs ) );

    }

        /**
         * Attempts to find the id(s) from the arguments.
         * @return      array
         */
        private function _getIDs() {
            
            $_aIDs = array();
            
            // The id parameter - the id parameter can accept comma delimited ids.
            if ( isset( $this->aArguments[ 'id' ] ) ) {
                if ( is_string( $this->aArguments[ 'id' ] ) || is_integer( $this->aArguments[ 'id' ] ) ) {
                    $_aIDs = array_merge( 
                        $this->getExploded( 
                            $this->aArguments[ 'id' ], 
                            "," 
                        ), 
                        $_aIDs 
                    );
                } else if ( is_array( $this->aArguments[ 'id' ] ) ) {
                    // The Auto-insert feature passes the id as array.
                    $_aIDs = $this->aArguments[ 'id' ];
                }
            }
                
            // The label parameter.
            if ( isset( $this->aArguments[ 'label' ] ) ) {
                
                $this->aArguments[ 'label' ] = $this->getExploded( 
                    $this->aArguments[ 'label' ], 
                    "," 
                );
                $_aIDs = array_merge(
                    $this->_getPostIDsByLabel( 
                        $this->aArguments[ 'label' ], 
                        isset( $this->aArguments[ 'operator' ] ) 
                            ? $this->aArguments[ 'operator' ] 
                            : null 
                    ), 
                    $_aIDs 
                );
                
            }
            $_aIDs      = array_unique( $_aIDs );
            return $_aIDs;
            
        }
        
        /**
         * Returns the unit output by post (unit) ID.
         */
        private function _getOutputByID( $iPostID ) {

            /**
             * The auto-insert sets the 'id' as array storing multiple ids. But this method is called per ID so the ID should be discarded.
             * if the unit gets deleted, auto-insert causes an error for not finding the options.
             */            
            $_aExternalOptions = array(
                    'id' => $iPostID,
                )
                + $this->aArguments
                + $this->getPostMeta( $iPostID, 
                    null,   // key - do not set to retrieve all
                    '_'     // prefix to remove
                )
                + array( 
                    'type' => null,
                );    
            return $this->_getOutputByExternalType( 
                $_aExternalOptions[ 'type' ],
                $_aExternalOptions 
            );
   
        }
           
            /**
             * 
             * @return      string      The unit output
             */
            private function _getOutputByExternalType( $sExternalType, $aArguments ) {
                return apply_filters( 
                    Externals_Registry::HOOK_SLUG . '_filter_external_output_' . $sExternalType,
                    '', // output string 
                    $aArguments // arguments
                );                  
            }            
            
        /**
         * Retrieves the post (unit) IDs by the given unit label.
         */
        private function _getPostIDsByLabel( $aLabels, $sOperator ) {
            
            // Retrieve the taxonomy slugs of the given taxonomy names.
            $_aTermSlugs = array();
            foreach( ( array ) $aLabels as $_sTermName ) {                
                $_aTerm         = get_term_by( 
                    'name', 
                    $_sTermName, 
                    Externals_Registry::$aTaxonomies[ 'tag' ], 
                    ARRAY_A 
                );
                $_aTermSlugs[]  = $_aTerm[ 'slug' ];
                
            }
            return $this->_getPostIDsByTag( $_aTermSlugs, 'slug', $sOperator );
            
        }
            /**
             * Retrieves post (unit) IDs by the plugin tag taxonomy slug.
             */
            private function _getPostIDsByTag( $aTermSlugs, $sFieldType='slug', $sOperator='AND' ) {

                if ( empty( $aTermSlugs ) ) { 
                    return array(); 
                }
                    
                $_aPostObjects = get_posts( 
                    array(
                        'post_type'         => Externals_Registry::$aPostTypes[ 'external' ],    
                        'posts_per_page'    => -1, // ALL posts
                        'tax_query'         => array(
                            array(
                                'taxonomy'  => Externals_Registry::$aTaxonomies[ 'tag' ],
    // @todo it should be possible to set ID here so that the result only contains post IDs                            
                                'field'     => $this->_sanitizeFieldKey( $sFieldType ),    // id or slug
                                'terms'     => $aTermSlugs, // the array of term slugs
                                'operator'  => $this->_sanitizeOperator( $sOperator ),    // 'IN', 'NOT IN', 'AND. If the item is only one, use AND.
                            )
                        )
                    )
                );
                $_aIDs = array();
                foreach( $_aPostObjects as $oPost ) {
                    $_aIDs[] = $oPost->ID;
                }
                return array_unique( $_aIDs );
                
            }
                private function _sanitizeFieldKey( $sField ) {
                    switch( strtolower( trim( $sField ) ) ) {
                        case 'id':
                            return 'id';
                        default:
                        case 'slug':
                            return 'slug';
                    }        
                }
                private function _sanitizeOperator( $sOperator ) {
                    switch( strtoupper( trim( $sOperator ) ) ) {
                        case 'NOT IN':
                            return 'NOT IN';
                        case 'IN':
                            return 'IN';
                        default:
                        case 'AND':
                            return 'AND';
                    }
                }        
        
}