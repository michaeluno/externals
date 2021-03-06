<?php 
/**
	Admin Page Framework v3.7.10b03 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/externals>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class Externals_AdminPageFramework_Form_View___Script_Form extends Externals_AdminPageFramework_Form_View___Script_Base {
    static public function getScript() {
        return <<<JAVASCRIPTS
( function( $ ) {

    var _removeExternals_AdminPageFrameworkLoadingOutputs = function() {

        jQuery( '.externals-form-loading' ).remove();
        jQuery( '.externals-form-js-on' )
            .hide()
            .css( 'visibility', 'visible' )
            .fadeIn( 200 )
            .removeClass( '.externals-form-js-on' );
    
    }

    /**
     * When some plugins or themes have JavaScript errors and the script execution gets stopped,
     * remove the style that shows "Loading...".
     */
    var _oneerror = window.onerror;
    window.onerror = function(){
        
        // We need to show the form.
        _removeExternals_AdminPageFrameworkLoadingOutputs();
        
        // Restore the original
        window.onerror = _oneerror;
        
        // If the original object is a function, execute it;
        // otherwise, discontinue the script execution and show the error message in the console.
        return "function" === typeof _oneerror
            ? _oneerror()      
            : false; 
       
    }
    
    /**
     * Rendering forms is heavy and unformatted layouts will be hidden with a script embedded in the head tag.
     * Now when the document is ready, restore that visibility state so that the form will appear.
     */
    jQuery( document ).ready( function() {
        _removeExternals_AdminPageFrameworkLoadingOutputs();
    });    

    /**
     * Gets triggered when a widget of the framework is saved.
     * @since    3.7.0
     */
    $( document ).bind( 'admin_page_framework_saved_widget', function( event, oWidget ){
        jQuery( '.externals-form-loading' ).remove();
    });    
    
}( jQuery ));
JAVASCRIPTS;
        
    }
    static private $_bLoadedTabEnablerScript = false;
}