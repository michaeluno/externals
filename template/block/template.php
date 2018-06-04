<?php
/*
 * Available variables:
 * 
 * $aOptions - the plugin options
 * $aItems - the fetched product links
 * $aArguments - the user defined arguments such as image size and count etc.
 */
?>

<?php if ( empty( $aItems ) ) : ?>
    <div><p><?php _e( 'No external item found.', 'externals' ); ?></p></div>  
    <?php return true; ?>
<?php endif; ?>    

    
<div class="external-block-container text">
<?php foreach( $aItems as $_sItem ) : ?>
    <div class="external-block-item"><pre><code><?php echo esc_html( $_sItem[ 'content' ] ); ?></code></pre></div>
<?php endforeach; ?>    
</div>