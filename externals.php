<?php
/*
	Plugin Name:    Externals
	Plugin URI:     http://en.michaeluno.jp/externals
	Description:    Displays external contents.
	Author:         Michael Uno (miunosoft)
	Author URI:     http://michaeluno.jp
	Version:        0.3.14
*/

/**
 * Provides the basic information about the plugin.
 * 
 * @since       1
 */
class Externals_Registry_Base {
 
	const VERSION        = '0.3.14';    // <--- DON'T FORGET TO CHANGE THIS AS WELL!!
	const NAME           = 'Externals';
	const DESCRIPTION    = 'Displays external contents.';
	const URI            = 'http://en.michaeluno.jp/externals';
	const AUTHOR         = 'miunosoft (Michael Uno)';
	const AUTHOR_URI     = 'http://en.michaeluno.jp/';
	const PLUGIN_URI     = 'http://en.michaeluno.jp/externals';
	const COPYRIGHT      = 'Copyright (c) 2013-2014, Michael Uno';
	const LICENSE        = 'GPL v2 or later';
	const CONTRIBUTORS   = '';
 
}

// Do not load if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
    return; 
}

/**
 * Provides the common data shared among plugin files.
 * 
 * To use the class, first call the setUp() method, which sets up the necessary properties.
 * 
 * @package     Externals
 * @since		1
*/
final class Externals_Registry extends Externals_Registry_Base {
    
	const TEXT_DOMAIN               = 'externals';
	const TEXT_DOMAIN_PATH          = '/language';
    
    /**
     * The hook slug used for the prefix of action and filter hook names.
     * 
     * @remark      The ending underscore is not necessary.
     */    
	const HOOK_SLUG                 = 'externals';    // without trailing underscore
    
    /**
     * The transient prefix. 
     * 
     * @remark      This is also accessed from uninstall.php so do not remove.
     * @remark      Up to 8 characters as transient name allows 45 characters or less ( 40 for site transients ) so that md5 (32 characters) can be added
     */    
	const TRANSIENT_PREFIX          = 'ETN';
    	    
    /**
     * 
     * @since       1
     */
    static public $sFilePath;  
    
    /**
     * 
     * @since        1
     */    
    static public $sDirPath;    
    
    /**
     * @since       1
     */
    static public $aOptionKeys = array(
    
        'setting'           => 'externals', 
        'table_versions'    => array(
            // $aDatabaseTables property key => {table name}_version
            'externals_request_cache' => 'externals_request_cache_version',
        ),
        
        'template'          => 'externals_templates',

    );
        
    /**
     * Used admin pages.
     * @since       1
     */
    static public $aAdminPages = array(
        // key => 'page slug'    
        'add_new'          => 'externals_add_new',
        'setting'          => 'externals_settings', // Settings - used to be const PageSettingsSlug         
        'template'         => 'externals_templates',
        'help'             => 'externals_help',
    );
    
    /**
     * Used post types.
     */
    static public $aPostTypes = array(
        'external'        => 'externals',
    );
    
    /**
     * Used post types by meta boxes.
     */
    static public $aMetaBoxPostTypes = array(
        // 'page'      => 'page',
        // 'post'      => 'post',
    );
    
    /**
     * Used taxonomies.
     */
    static public $aTaxonomies = array(
        'tag'   => 'externals_tag',
    );
    
    /**
     * Used shortcode slugs
     */
    static public $aShortcodes = array(
        'main'  => 'externals',
    );
    
    /**
     * Stores custom database table names.
     * @remark      slug (part of class file name) => table name
     * @since       1
     */
    static public $aDatabaseTables = array(
        'externals_request_cache' => array(
            'name'              => 'externals_request_cache',  // serves as the table name suffix
            'version'           => '1.0.0',
            'across_network'    => true,
            'class_name'        => 'Externals_DatabaseTable_externals_request_cache',
        ),
    );
    /**
     * Stores the database table versions.
     * @since       1
     * @deprecated  0.3.13
     */
//    static public $aDatabaseTableVersions = array(
//        'request_cache' => '1.0.0',
//    );
    
    /**
     * Sets up class properties.
     * @return      void
     */
	static function setUp( $sPluginFilePath ) {
        self::$sFilePath = $sPluginFilePath; 
        self::$sDirPath  = dirname( self::$sFilePath );  
	}	
	
    /**
     * @return      string
     */
	public static function getPluginURL( $sRelativePath='' ) {
		return plugins_url( $sRelativePath, self::$sFilePath );
	}

    /**
     * Requirements.
     * @since       1
     */    
    static public $aRequirements = array(
        'php' => array(
            'version'   => '5.2.4',
            'error'     => 'The plugin requires the PHP version %1$s or higher.',
        ),
        'wordpress'         => array(
            'version'   => '3.4',   // uses $wpdb->delete()
            'error'     => 'The plugin requires the WordPress version %1$s or higher.',
        ),
        // 'mysql'             => array(
            // 'version'   => '5.0.3', // uses VARCHAR(2083) 
            // 'error'     => 'The plugin requires the MySQL version %1$s or higher.',
        // ),
        'functions'     => '', // disabled
        // array(
            // e.g. 'mblang' => 'The plugin requires the mbstring extension.',
        // ),
        'classes'       => array(
            'DOMDocument' => 'The plugin requires the DOMXML extension.',
        ),
        'constants'     => '', // disabled
        // array(
            // e.g. 'THEADDONFILE' => 'The plugin requires the ... addon to be installed.',
            // e.g. 'APSPATH' => 'The script cannot be loaded directly.',
        // ),
        'files'         => '', // disabled
        // array(
            // e.g. 'home/my_user_name/my_dir/scripts/my_scripts.php' => 'The required script could not be found.',
        // ),
    );        
	
}
Externals_Registry::setUp( __FILE__ );

// Run the bootstrap script.    
include( dirname( __FILE__ ).'/include/library/apf/admin-page-framework.php' );
include( dirname( __FILE__ ).'/include/class/Externals_Bootstrap.php' );
new Externals_Bootstrap(
    Externals_Registry::$sFilePath,
    Externals_Registry::HOOK_SLUG    // hook prefix    
);