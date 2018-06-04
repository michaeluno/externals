<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Provides methods for template options.
 * 
 * @since       1
 */
class Externals_TemplateOption extends Externals_Option_Base {
  
    /**
     * Caches the active templates.
     * 
     * @since       1    
     */
    private static $_aActiveTemplates = array();
    
    /**
     * Represents the structure of the template option array.
     * @since       1
     */
    public static $aStructure_Template = array(

        'relative_dir_path' => null,  // (string)
        'id'                => null,  // (string)
        'is_active'         => null,  // (booean)
        'index'             => null,  // (integer)
        'name'              => null,  // (string)   will be used to list templates in options.

        // for listing table
        'description'       => null,
        'version'           => null,
        'author'            => null,
        'author_uri'        => null,
                    
    );
        
    /**
     * Stores the self instance.
     */
    static public $oSelf;
    
    /**
     * Sets up properties.
     */
    public function __construct( $sOptionKey ) {
        parent::__construct( $sOptionKey );
        
        add_filter(
            Externals_Registry::HOOK_SLUG . '_filter_default_template_path',
            array( $this, 'replyToReturnDefaultTemplatePath' )
        );
        
    }
        /**
         * @callback    externals_filter_default_template_path
         * @return      string
         */
        public function replyToReturnDefaultTemplatePath( $sPath ) {
            $_aTemplate       = $this->getTemplateArrayByDirPath(
                Externals_Registry::$sDirPath 
                    . DIRECTORY_SEPARATOR . 'template'
                    . DIRECTORY_SEPARATOR . 'text',
                false       // no extra info
            );
            return ABSPATH . $_aTemplate[ 'relative_dir_path' ] . '/template.php';
        }
    
    /**
     * Returns an instance of the self.
     * 
     * @remark      To support PHP 5.2, this method needs to be defined in each extended class 
     * as in static methods, it is not possible to retrieve the extended class name in a base class in PHP 5.2.x.
     * @return      object
     */
    static public function getInstance( $sOptionKey='' ) {
        
        if ( isset( self::$oSelf ) ) {
            return self::$oSelf;
        }
        $sOptionKey = $sOptionKey 
            ? $sOptionKey
            : Externals_Registry::$aOptionKeys[ 'template' ];        
        
        $_sClassName = __Class__;
        self::$oSelf = new $_sClassName( $sOptionKey );            
        return self::$oSelf;
        
    }
    
    /**
     * Returns the formatted options array.
     * @return  array
     */    
    protected function _getFormattedOptions( $sOptionKey ) {
        
        $_aOptions = parent::_getFormattedOptions( $sOptionKey );
        return $_aOptions + $this->_getDefaultTemplates();
        
    }    
        /**
         * @return      array       plugin default templates which should be activated upon installation / restoring factory default.
         */
        private function _getDefaultTemplates() {
            
            $_aDirPaths = array(
                Externals_Registry::$sDirPath . '/template/text',
            );
            $_iIndex     = 0;
            $_aTemplates = array();
            foreach( $_aDirPaths as $_sDirPath ) {
                $_aTemplate = $this->getTemplateArrayByDirPath( $_sDirPath );
                if ( ! $_aTemplate ) {
                    continue;
                }
                $_aTemplate[ 'is_active' ] = true;
                $_aTemplate[ 'index' ] = ++$_iIndex;
                $_aTemplates[ $_aTemplate[ 'id' ] ] = $_aTemplate;
            }
            return $_aTemplates;
         
        }
    
    /**
     * Returns an array that holds arrays of activated template information.
     * 
     * @since       unknown
     * @since       1           moved from the templates class.
     * @scope       public      It is accessed from the template loader class.
     */
    public function getActiveTemplates() {
        
        if ( ! empty( self::$_aActiveTemplates ) ) {
            return self::$_aActiveTemplates;
        }
                
        $_aActiveTemplates = $this->_getActiveTemplatesExtracted( 
            $this->get()    // saved all templates
        );

        // Cache
        self::$_aActiveTemplates = $_aActiveTemplates;
        return $_aActiveTemplates;
        
    }
        /**
         * @since       3.3.0
         * @return      array
         */
        private function _getActiveTemplatesExtracted( array $aTemplates ) {
            
            // $_aActive = array();
            foreach( $aTemplates as $_sID => $_aTemplate ) {
                
                $_aTemplate = $this->_formatTemplateArray( $_aTemplate );

                // Remove inactive templates.
                if ( ! $this->getElement( $_aTemplate, 'is_active' ) ) {
                    unset( $aTemplates[ $_sID ] );
                    continue;
                }
                
                // Backward compatibility for the v2 options structure.
                // If the id is not a relative dir path,
                if ( 
                    $_sID !== $_aTemplate[ 'relative_dir_path' ] 
                ) {
                    
                    // Remove the old item.
                    unset( $aTemplates[ $_sID ] );
                    
                    // If the same ID already exists, set the old id.
                    if ( isset( $aTemplates[ $_aTemplate[ 'relative_dir_path' ] ] ) ) {
                        $aTemplates[ $_aTemplate[ 'relative_dir_path' ] ][ 'old_id' ] = $_sID;
                    } else {                    
                        $aTemplates[ $_aTemplate[ 'relative_dir_path' ] ] = $_aTemplate[ 'relative_dir_path' ];
                    }
                    
                }
                
                $_aTemplate[ 'is_active' ] = true;
                                
                
            }
            return $aTemplates;
            
        }

        /**
         * Formats the template array.
         * 
         * Takes care of formatting change through version updates.
         * 
         * @since       1              
         * @return      array|boolean       Formatted template array. If the passed value is not an array 
         * or something wrong with the template array, false will be returned.
         */
        protected function _formatTemplateArray( $aTemplate ) {
         
            if ( ! is_array( $aTemplate ) ) { 
                return false; 
            }
            
            $aTemplate = $aTemplate + self::$aStructure_Template;                       
            $aTemplate[ 'relative_dir_path' ] = $this->getElement(
                $aTemplate,
                'relative_dir_path',
                ''
            );                 
            $aTemplate[ 'relative_dir_path' ] = $this->getPathSanitized(
                $aTemplate[ 'relative_dir_path' ]
            );
            
            $_sDirPath = $this->getAbsolutePathFromRelative( $aTemplate[ 'relative_dir_path' ] );
                        
            // Check required files. Consider the possibility that the user may directly delete the template files/folders.
            $_aRequiredFiles = array(
                $_sDirPath . DIRECTORY_SEPARATOR . 'style.css',
                $_sDirPath . DIRECTORY_SEPARATOR . 'template.php',             
            );
            if ( ! $this->doFilesExist( $_aRequiredFiles ) ) {
                return false;
            }                                    

            $aTemplate[ 'id' ]                = $this->getElement(
                $aTemplate,
                'id',
                $aTemplate[ 'relative_dir_path' ]
            );     

            // For uploaded templates
            $aTemplate[ 'name' ]              = $this->getElement(
                $aTemplate,
                'name',
                ''
            );     
            $aTemplate[ 'description' ]       = $this->getElement(
                $aTemplate,
                'description',
                ''
            );     
            $aTemplate[ 'version' ]            = $this->getElement(
                $aTemplate,
                'version',
                ''
            );     
            $aTemplate[ 'author' ]             = $this->getElement(
                $aTemplate,
                'author',
                ''
            );     
            $aTemplate[ 'author_uri' ]         = $this->getElement(
                $aTemplate,
                'author_uri',
                ''
            );     
            $aTemplate[ 'is_active' ]          = $this->getElement(
                $aTemplate,
                'is_active',
                false
            );     
              
            return $aTemplate;
            
        }    
            
 
    /**
     * Retrieves the label(name) of the template by template id
     * 
     * @remark            Used when rendering the post type table of units.
     */ 
    public function getTemplateNameByID( $sTemplateID ) {
        return $this->get(
            array( $sTemplateID, 'name' ), // dimensional keys
            '' // default
        );    
    }
 
 
    /**
     * Returns an array holding active template labels.
     * @since       1
     */
    public function getActiveTemplateLabels() {        
        $_aLabels = array();
        foreach( $this->getActiveTemplates() as $_aTemplate ) {
            $_aLabels[ $_aTemplate[ 'id' ] ] = $_aTemplate[ 'name' ];
        }
        return $_aLabels;
    }
    /**
     * 
     * @since       1
     * @return      string
     */
    public function getDefaultTemplateIDByExternalType( $sExternalType ) {
        $_sTemplateDirPath = apply_filters(
            Externals_Registry::HOOK_SLUG . '_filter_default_template_directory_path_of_' . $sExternalType,
            Externals_Registry::$sDirPath 
                . DIRECTORY_SEPARATOR . 'template'
                . DIRECTORY_SEPARATOR . 'text'
        );

        $_aTemplate = $this->getTemplateArrayByDirPath(
            $_sTemplateDirPath,
            false       // no extra info
        );
        return $_aTemplate[ 'id' ];
    }

    /**
     * Caches the uploaded templates.
     * 
     * @since       1    
     */
    private static $_aUploadedTemplates = array();
 
    /**
     * Retrieve templates and returns the template information as array.
     * 
     * This method is called for the template listing table to list available templates. So this method generates the template information dynamically.
     * This method does not deal with saved options.
     * 
     * @return      array
     */
    public function getUploadedTemplates() {
            
        if ( ! empty( self::$_aUploadedTemplates ) ) {
            return self::$_aUploadedTemplates;
        }
            
        // Construct a template array.
        $_aTemplates = array();
        $_iIndex     = 0;        
        foreach( $this->_getTemplateDirs() as $_sDirPath ) {
            
            $_aTemplate = $this->getTemplateArrayByDirPath( $_sDirPath );
            if ( ! $_aTemplate ) {
                continue;
            }
            
            // Uploaded templates are supposed to be only called in the admin template listing page.
            // So by default, these are not active.
            $_aTemplate[ 'is_active' ] = false;
            
            $_aTemplate[ 'index' ] = ++$_iIndex;
            $_aTemplates[ $_aTemplate[ 'id' ] ] = $_aTemplate;
            
        }
        
        self::$_aUploadedTemplates = $_aTemplates;
        return $_aTemplates;
        
    }
        /**
         * Returns the template array by the given directory path.
         * @since       1
         * @scope       public       The unit class also accesses this.
         */
        public function getTemplateArrayByDirPath( $sDirPath, $bExtraInfo=true ) {
                        
            $_sRelativePath = $this->getPathSanitized( 
                untrailingslashit( $this->getRelativePath( ABSPATH, $sDirPath ) ) 
            );
            $_aData         = array(
                'relative_dir_path'     => $_sRelativePath,
                'id'                    => $_sRelativePath,
            );
            
            if ( $bExtraInfo ) {               
                $_aData[ 'thumbnail_path' ] = $this->_getScreenshotPath( $sDirPath );
                return $this->_formatTemplateArray( 
                    $this->getTemplateData( $sDirPath . DIRECTORY_SEPARATOR . 'style.css' )
                    + $_aData 
                );
            }
            return $this->_formatTemplateArray( $_aData );
        }        
            /**
             * @return  string|null
             */
            protected function _getScreenshotPath( $sDirPath ) {
                foreach( array( 'jpg', 'jpeg', 'png', 'gif' ) as $sExt ) {
                    if ( file_exists( $sDirPath . DIRECTORY_SEPARATOR . 'screenshot.' . $sExt ) ) { 
                        return $sDirPath . DIRECTORY_SEPARATOR . 'screenshot.' . $sExt;
                    }
                }
                return null;
            }           
    
        /**
         * Stores the read template directory paths.
         * @since       1    
         */
        static private $_aTemplateDirs = array();
        
        /**
         * Returns an array holding the template directories.
         * 
         * @since       1
         * @return      array       Contains list of template directory paths.
         */
        private function _getTemplateDirs() {
                
            if ( ! empty( self::$_aTemplateDirs ) ) {
                return self::$_aTemplateDirs;
            }
            foreach( $this->_getTemplateContainerDirs() as $_sTemplateDirPath ) {
                    
                if ( ! @file_exists( $_sTemplateDirPath  ) ) { 
                    continue; 
                }
                $_aFoundDirs = glob( $_sTemplateDirPath . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR );
                if ( is_array( $_aFoundDirs ) ) {    // glob can return false
                    self::$_aTemplateDirs = array_merge( 
                        $_aFoundDirs, 
                        self::$_aTemplateDirs 
                    );
                }
                                
            }
            self::$_aTemplateDirs = array_unique( self::$_aTemplateDirs );
            self::$_aTemplateDirs = ( array ) apply_filters( 'externals_filter_template_directories', self::$_aTemplateDirs );
            self::$_aTemplateDirs = array_filter( self::$_aTemplateDirs );    // drops elements of empty values.
            self::$_aTemplateDirs = array_unique( self::$_aTemplateDirs );
            return self::$_aTemplateDirs;
        
        }    
            /**
             * Returns the template container directories.
             * @since       1
             */
            private function _getTemplateContainerDirs() {
                
                $_aTemplateContainerDirs    = array();
                $_aTemplateContainerDirs[]  = Externals_Registry::$sDirPath . DIRECTORY_SEPARATOR . 'template';
                $_aTemplateContainerDirs[]  = get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'externals';
                $_aTemplateContainerDirs    = apply_filters( 'externals_filter_template_container_directories', $_aTemplateContainerDirs );
                $_aTemplateContainerDirs    = array_filter( $_aTemplateContainerDirs );    // drop elements of empty values.
                return array_unique( $_aTemplateContainerDirs );
                
            }       
    
 
    /**
     * A helper function for the getUploadedTemplates() method.
     * 
     * Used when rendering the template listing table.
     * An alternative to get_plugin_data() as some users change the location of the wp-admin directory.
     * 
     * @return      array       an array of template detail information from the given file path.
     * */
    protected function getTemplateData( $sCSSPath )    {

        return file_exists( $sCSSPath )
            ? get_file_data( 
                $sCSSPath, 
                array(
                    'name'           => 'Template Name',
                    'template_uri'   => 'Template URI',
                    'version'        => 'Version',
                    'description'    => 'Description',
                    'author'         => 'Author',
                    'author_uri'     => 'Author URI',
                ),
                '' // context - do not set any
            )
            : array();
        
    }                     
        
}