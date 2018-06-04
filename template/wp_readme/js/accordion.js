(function($){
    
    var parseBool = function( b ) {
        return !(/^(false|0)$/i).test(b) && !!b;
    }
    
    jQuery( document ).ready( function() {

        // console.log( 'called' );
        // console.log( externals_wp_readme_accordion );
            
        jQuery.each( externals_wp_readme_accordion, function( isIndex, _aAccordion ) {

            var _aOptions = jQuery.extend( _aAccordion, {
                header: 'h3',
                collapsible: true,
                heightStyle: "content",
            } );
            if ( parseBool( _aAccordion[ 'collapsed' ] ) ) {
                _aOptions[ 'active' ] = false;
            }
            jQuery( "#" + isIndex ).accordion( _aOptions );


        });        

    });

}( jQuery ));