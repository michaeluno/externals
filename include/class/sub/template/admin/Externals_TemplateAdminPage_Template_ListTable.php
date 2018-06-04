<?php
/**
 * Externals
 * 
 * 
 * http://en.michaeluno.jp/externals/
 * Copyright (c) 2015 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the 'Installed' tab to the 'Templates' page of the loader plugin.
 * 
 * @since       1
 * @extends     Externals_AdminPage_Tab_Base
 */
class Externals_TemplateAdminPage_Template_ListTable extends Externals_AdminPage_Tab_Base {
    
    /**
     * Triggered when the tab is loaded.
     * 
     * @callback        load_{page slug}_{tab slug}
     */
    public function replyToLoadTab( $oAdminPage ) {
            
        // Set the list table data.
        $_oTemplateOption = Externals_TemplateOption::getInstance();
        $this->oTemplateListTable = new Externals_ListTable_Template( 
            $_oTemplateOption->getActiveTemplates() // precedence
            + $_oTemplateOption->getUploadedTemplates() // merge 
        );
        $this->oTemplateListTable->process_bulk_action();
        
    }

    /**
     * 
     * @callback        do_{page slug}_{tab slug}
     */
    public function replyToDoTab( $oFactory ) {
      
        $this->oTemplateListTable->prepare_items();
        ?>
        <form id="template-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 'externals_templates'; ?>" />
            <input type="hidden" name="tab" value="<?php echo isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'table'; ?>" />
            <input type="hidden" name="post_type" value="<?php echo isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : Externals_Registry::$aPostTypes[ 'external' ]; ?>" />
            <!-- Now we can render the completed list table -->
            <?php $this->oTemplateListTable->display() ?>
        </form>        
        <?php
                
    }    
        
    public function replyToDoAfterTab( $oFactory ) {
        
        $_oOption = Externals_Option::getInstance();
        if ( ! $_oOption->isDebugMode() ) {
            return;
        }
        
        echo "<h3>" 
                . __( 'Debug', 'externals' ) 
            . "</h3>";
            
        echo "<h4>" 
                . __( 'Raw Template Option Values', 'externals' ) 
            . "</h4>";
        echo $oFactory->oDebug->get(
            get_option(
                Externals_Registry::$aOptionKeys[ 'template' ],
                array()
            )
            
        );            
        
        echo "<h4>" 
                . __( 'Data of Active Templates', 'externals' ) 
            . "</h4>";        
        echo $oFactory->oDebug->get(
            Externals_TemplateOption::getInstance()->get()
        );
        
    }
}
