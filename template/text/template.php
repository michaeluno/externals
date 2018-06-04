<?php
/*
 * Available variables:
 * 
 * $aOptions - the plugin options
 * $aItems - the fetched product links
 * $aArguments - the user defined arguments such as image size and count etc.
 */
// Externals_Debug::log( $aItems );
?>

<?php if ( empty( $aItems ) ) : ?>
    <div><p><?php _e( 'No external item found.', 'externals' ); ?></p></div>  
    <?php return true; ?>
<?php endif; ?>    

    
<div class="external-text-container">
<?php foreach( $aItems as $_sItem ) : ?>
    <div class="external-text-item"><?php echo $_sItem[ 'content' ]; ?></div>
<?php endforeach; ?>    
</div>