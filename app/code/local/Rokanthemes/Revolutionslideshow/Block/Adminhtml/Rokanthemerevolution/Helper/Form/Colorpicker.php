<?php

class Rokanthemes_Revolutionslideshow_Block_Adminhtml_Rokanthemerevolution_Helper_Form_Colorpicker extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
	    $this->setType('text');
    }

	public function getElementHtml()
	{
		$html = parent::getElementHtml();
		$html.= $this->_getElementJavascript();
		return $html;
	}

	protected function _getElementJavascript()
	{
		$html = '<script>jQuery(function(){ jQuery("#'.$this->getHtmlId().'").attr("style", "width: 200px !important").attr("data-hex", true).mColorPicker({ imageFolder: "'.Mage::helper('core/js')->getJsUrl('rokanthemes/mColorPicker/').'" }); });</script>';
		return $html;
	}

	public function getHtmlAttributes()
	{
		return array('type', 'title', 'class', 'style', 'onclick', 'onchange', 'onkeyup', 'disabled', 'readonly', 'maxlength', 'tabindex');
	}
}
