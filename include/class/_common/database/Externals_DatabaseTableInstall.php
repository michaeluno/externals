<?php
/**
 * Externals
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno
 * 
 */

/**
 * Creates custom database tables for the plugin.
 * 
 * @since       1
 */
class Externals_DatabaseTableInstall extends Externals_WPUtility {

    /**
     * 
     */
    public function __construct( $bInstallOrUninstall ) {

        $_sMethodName = $bInstallOrUninstall
            ? 'install'
            : 'uninstall';

        foreach( Externals_Registry::$aDatabaseTables as $_sKey => $_aArguments ) {
            $_sClassName = "Externals_DatabaseTable_{$_sKey}";
            $_oTable     = new $_sClassName;
            $_oTable->$_sMethodName();
        }

    }
   
}