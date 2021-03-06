<?php 
/**
	Admin Page Framework v3.7.10b03 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/externals>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class Externals_AdminPageFramework_Message {
    public $aMessages = array();
    public $aDefaults = array('option_updated' => 'The options have been updated.', 'option_cleared' => 'The options have been cleared.', 'export' => 'Export', 'export_options' => 'Export Options', 'import_options' => 'Import', 'import_options' => 'Import Options', 'submit' => 'Submit', 'import_error' => 'An error occurred while uploading the import file.', 'uploaded_file_type_not_supported' => 'The uploaded file type is not supported: %1$s', 'could_not_load_importing_data' => 'Could not load the importing data.', 'imported_data' => 'The uploaded file has been imported.', 'not_imported_data' => 'No data could be imported.', 'upload_image' => 'Upload Image', 'use_this_image' => 'Use This Image', 'insert_from_url' => 'Insert from URL', 'reset_options' => 'Are you sure you want to reset the options?', 'confirm_perform_task' => 'Please confirm your action.', 'specified_option_been_deleted' => 'The specified options have been deleted.', 'nonce_verification_failed' => 'A problem occurred while processing the form data. Please try again.', 'check_max_input_vars' => 'Not all form fields could not be sent. Please check your server settings of PHP <code>max_input_vars</code> and consult the server administrator to increase the value. <code>max input vars</code>: %1$s. <code>$_POST</code> count: %2$s', 'send_email' => 'Is it okay to send the email?', 'email_sent' => 'The email has been sent.', 'email_scheduled' => 'The email has been scheduled.', 'email_could_not_send' => 'There was a problem sending the email', 'title' => 'Title', 'author' => 'Author', 'categories' => 'Categories', 'tags' => 'Tags', 'comments' => 'Comments', 'date' => 'Date', 'show_all' => 'Show All', 'show_all_authors' => 'Show all Authors', 'powered_by' => 'Thank you for creating with', 'and' => 'and', 'settings' => 'Settings', 'manage' => 'Manage', 'select_image' => 'Select Image', 'upload_file' => 'Upload File', 'use_this_file' => 'Use This File', 'select_file' => 'Select File', 'remove_value' => 'Remove Value', 'select_all' => 'Select All', 'select_none' => 'Select None', 'no_term_found' => 'No term found.', 'select' => 'Select', 'insert' => 'Insert', 'use_this' => 'Use This', 'return_to_library' => 'Return to Library', 'queries_in_seconds' => '%1$s queries in %2$s seconds.', 'out_of_x_memory_used' => '%1$s out of %2$s MB (%3$s) memory used.', 'peak_memory_usage' => 'Peak memory usage %1$s MB.', 'initial_memory_usage' => 'Initial memory usage  %1$s MB.', 'allowed_maximum_number_of_fields' => 'The allowed maximum number of fields is {0}.', 'allowed_minimum_number_of_fields' => 'The allowed minimum number of fields is {0}.', 'add' => 'Add', 'remove' => 'Remove', 'allowed_maximum_number_of_sections' => 'The allowed maximum number of sections is {0}', 'allowed_minimum_number_of_sections' => 'The allowed minimum number of sections is {0}', 'add_section' => 'Add Section', 'remove_section' => 'Remove Section', 'toggle_all' => 'Toggle All', 'toggle_all_collapsible_sections' => 'Toggle all collapsible sections', 'reset' => 'Reset', 'yes' => 'Yes', 'no' => 'No', 'on' => 'On', 'off' => 'Off', 'enabled' => 'Enabled', 'disabled' => 'Disabled', 'supported' => 'Supported', 'not_supported' => 'Not Supported', 'functional' => 'Functional', 'not_functional' => 'Not Functional', 'too_long' => 'Too Long', 'acceptable' => 'Acceptable', 'no_log_found' => 'No log found.', 'method_called_too_early' => 'The method is called too early.', 'debug_info' => 'Debug Info', 'click_to_expand' => 'Click here to expand to view the contents.', 'click_to_collapse' => 'Click here to collapse the contents.', 'loading' => 'Loading...', 'please_enable_javascript' => 'Please enable JavaScript for better experience.');
    protected $_sTextDomain = 'externals';
    static private $_aInstancesByTextDomain = array();
    public static function getInstance($sTextDomain = 'externals') {
        $_oInstance = isset(self::$_aInstancesByTextDomain[$sTextDomain]) && (self::$_aInstancesByTextDomain[$sTextDomain] instanceof Externals_AdminPageFramework_Message) ? self::$_aInstancesByTextDomain[$sTextDomain] : new Externals_AdminPageFramework_Message($sTextDomain);
        self::$_aInstancesByTextDomain[$sTextDomain] = $_oInstance;
        return self::$_aInstancesByTextDomain[$sTextDomain];
    }
    public static function instantiate($sTextDomain = 'externals') {
        return self::getInstance($sTextDomain);
    }
    public function __construct($sTextDomain = 'externals') {
        $this->_sTextDomain = $sTextDomain;
        $this->aMessages = array_fill_keys(array_keys($this->aDefaults), null);
    }
    public function getTextDomain() {
        return $this->_sTextDomain;
    }
    public function set($sKey, $sValue) {
        $this->aMessages[$sKey] = $sValue;
    }
    public function get($sKey = '') {
        if (!$sKey) {
            return $this->_getAllMessages();
        }
        return isset($this->aMessages[$sKey]) ? __($this->aMessages[$sKey], $this->_sTextDomain) : __($this->{$sKey}, $this->_sTextDomain);
    }
    private function _getAllMessages() {
        $_aMessages = array();
        foreach ($this->aMessages as $_sLabel => $_sTranslation) {
            $_aMessages[$_sLabel] = $this->get($_sLabel);
        }
        return $_aMessages;
    }
    public function output($sKey) {
        echo $this->get($sKey);
    }
    public function __($sKey) {
        return $this->get($sKey);
    }
    public function _e($sKey) {
        $this->output($sKey);
    }
    public function __get($sPropertyName) {
        return isset($this->aDefaults[$sPropertyName]) ? $this->aDefaults[$sPropertyName] : $sPropertyName;
    }
    private function __doDummy() {
        __('The options have been updated.', 'externals');
        __('The options have been cleared.', 'externals');
        __('Export', 'externals');
        __('Export Options', 'externals');
        __('Import', 'externals');
        __('Import Options', 'externals');
        __('Submit', 'externals');
        __('An error occurred while uploading the import file.', 'externals');
        __('The uploaded file type is not supported: %1$s', 'externals');
        __('Could not load the importing data.', 'externals');
        __('The uploaded file has been imported.', 'externals');
        __('No data could be imported.', 'externals');
        __('Upload Image', 'externals');
        __('Use This Image', 'externals');
        __('Insert from URL', 'externals');
        __('Are you sure you want to reset the options?', 'externals');
        __('Please confirm your action.', 'externals');
        __('The specified options have been deleted.', 'externals');
        __('A problem occurred while processing the form data. Please try again.', 'externals');
        __('Not all form fields could not be sent. Please check your server settings of PHP <code>max_input_vars</code> and consult the server administrator to increase the value. <code>max input vars</code>: %1$s. <code>$_POST</code> count: %2$s', 'externals');
        __('Is it okay to send the email?', 'externals');
        __('The email has been sent.', 'externals');
        __('The email has been scheduled.', 'externals');
        __('There was a problem sending the email', 'externals');
        __('Title', 'externals');
        __('Author', 'externals');
        __('Categories', 'externals');
        __('Tags', 'externals');
        __('Comments', 'externals');
        __('Date', 'externals');
        __('Show All', 'externals');
        __('Show All Authors', 'externals');
        __('Thank you for creating with', 'externals');
        __('and', 'externals');
        __('Settings', 'externals');
        __('Manage', 'externals');
        __('Select Image', 'externals');
        __('Upload File', 'externals');
        __('Use This File', 'externals');
        __('Select File', 'externals');
        __('Remove Value', 'externals');
        __('Select All', 'externals');
        __('Select None', 'externals');
        __('No term found.', 'externals');
        __('Select', 'externals');
        __('Insert', 'externals');
        __('Use This', 'externals');
        __('Return to Library', 'externals');
        __('%1$s queries in %2$s seconds.', 'externals');
        __('%1$s out of %2$s MB (%3$s) memory used.', 'externals');
        __('Peak memory usage %1$s MB.', 'externals');
        __('Initial memory usage  %1$s MB.', 'externals');
        __('The allowed maximum number of fields is {0}.', 'externals');
        __('The allowed minimum number of fields is {0}.', 'externals');
        __('Add', 'externals');
        __('Remove', 'externals');
        __('The allowed maximum number of sections is {0}', 'externals');
        __('The allowed minimum number of sections is {0}', 'externals');
        __('Add Section', 'externals');
        __('Remove Section', 'externals');
        __('Toggle All', 'externals');
        __('Toggle all collapsible sections', 'externals');
        __('Reset', 'externals');
        __('Yes', 'externals');
        __('No', 'externals');
        __('On', 'externals');
        __('Off', 'externals');
        __('Enabled', 'externals');
        __('Disabled', 'externals');
        __('Supported', 'externals');
        __('Not Supported', 'externals');
        __('Functional', 'externals');
        __('Not Functional', 'externals');
        __('Too Long', 'externals');
        __('Acceptable', 'externals');
        __('No log found.', 'externals');
        __('The method is called too early: %1$s', 'externals');
        __('Debug Info', 'externals');
        __('Click here to expand to view the contents.', 'externals');
        __('Click here to collapse the contents.', 'externals');
        __('Loading...', 'externals');
        __('Please enable JavaScript for better experience.', 'externals');
    }
}