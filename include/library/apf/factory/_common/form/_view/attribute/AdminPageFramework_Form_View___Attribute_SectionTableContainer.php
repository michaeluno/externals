<?php 
/**
	Admin Page Framework v3.7.10b03 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/externals>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class Externals_AdminPageFramework_Form_View___Attribute_SectionTableContainer extends Externals_AdminPageFramework_Form_View___Attribute_Base {
    protected function _getAttributes() {
        $_aSectionAttributes = $this->uniteArrays($this->dropElementsByType($this->aArguments['attributes']), array('id' => $this->aArguments['_tag_id'], 'class' => $this->getClassAttribute('externals-section', $this->getAOrB($this->aArguments['section_tab_slug'], 'externals-tab-content', null), $this->getAOrB($this->aArguments['_is_collapsible'], 'is_subsection_collapsible', null)),));
        $_aSectionAttributes['class'] = $this->getClassAttribute($_aSectionAttributes['class'], $this->dropElementsByType($this->aArguments['class']));
        $_aSectionAttributes['style'] = $this->getStyleAttribute($_aSectionAttributes['style'], $this->getAOrB($this->aArguments['hidden'], 'display:none', null));
        return $_aSectionAttributes;
    }
}