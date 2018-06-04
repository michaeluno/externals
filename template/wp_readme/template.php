<?php
/*
 * Available variables:
 * 
 * $aOptions - the plugin options
 * $aItems - the fetched product links
 * $aArguments - the user defined arguments such as image size and count etc.
 */

 
$_oArgument = new Externals_AdminPageFramework_ArrayHandler( $aArguments );

// Load scripts
new Externals_Script_ReadMeTemplate( 
    $_oArgument->get( 'element_id' ),   // used for tag id attributes
    $aArguments,    // output arguments such as collapsible or not etc.
    $_oArgument->get( 'output_type' ) // type - such as accordion or tab
);

$_oUtil = new Externals_PluginUtility;

?>

<?php if ( empty( $aItems ) ) : ?>
    <div><p><?php _e( 'No external item found.', 'externals' ); ?></p></div>  
    <?php return true; ?>
<?php endif; ?>    

    
<div class="external-wp_readme-container">
<?php foreach( $aItems as $_aItem ) : ?>
<?php $_oItem = new Externals_AdminPageFramework_ArrayHandler( $_aItem ); ?>
    <div class="external-wp_readme-item">
        <?php echo $_oItem->get( 'content' ); ?>
        <?php 
            $_sLastModified = $_oItem->get( 'last-modified' );
            if ( $_sLastModified ) {
                echo "<span class='external-wp_readme-last-modified-date'>("
                        . __( 'Last modified: ', 'externals' )
                        . $_oUtil->getSiteReadableDate( strtotime( $_sLastModified ) )
                    . ")</span>";
            }
        ?>
    </div>
<?php endforeach; ?>    
</div>