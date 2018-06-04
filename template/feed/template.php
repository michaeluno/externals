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
        
<div class="external-feed-container">
<?php foreach( $aItems as $_aItem ) : ?>
    <div class="external-feed-item">
        <h2 class="external-feed-title"><a href="<?php echo esc_url( $_aItem[ 'permalink' ] ); ?>" target="_blank" rel="nofollow"><?php echo $_aItem[ 'title' ]; ?></a></h2>        
        <div class="external-feed-item-images"><?php 
        foreach( $_aItem[ 'images' ] as $_sIMG ) {
            echo "<div class='external-feed-item-image'>"
                    . $_sIMG
                . "</div>";
        } ?></div>
        <div class="external-feed-description">
            <div class="external-feed-meta">            
                <span class="external-feed-date"><?php echo human_time_diff( strtotime( $_aItem[ 'date' ] ), current_time( 'timestamp' ) ) . " " . __( 'ago' ); ?></span>
                <span class="external-feed-author"><?php echo $_aItem[ 'author' ]; ?></span>
                <span class="external-feed-source">from <a href="<?php echo esc_attr( $_aItem[ 'source' ] ); ?>" target="_blank"><?php echo parse_url( $_aItem[ 'source' ], PHP_URL_HOST ); ?></a></span>
            </div>
            <?php echo strip_tags( $_aItem[ 'description' ] ); ?>
        </div>
    </div>
<?php endforeach; ?>    
</div>