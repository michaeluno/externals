(function($){
    

    var parseBool = function( b ) {
        return !(/^(false|0)$/i).test(b) && !!b;
    }
    
    jQuery( document ).ready( function() {
     
        jQuery.each( externals_wp_readme_tabs, function( isIndex, _aTabs ) {

            var _aOptions = jQuery.extend( _aTabs, {
                collapsible: true,
            } );
            if ( parseBool( _aTabs[ 'collapsed' ] ) ) {
                _aOptions[ 'active' ] = false;
            }
            jQuery( "#" + isIndex ).tabs( _aOptions );

        });        

    });    
    
}( jQuery ));