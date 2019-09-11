<?php


class Rokanthemes_Revolutionslideshow_Block_Adminhtml_Rokanthemerevolution extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_rokanthemerevolution';
		$this->_blockGroup = 'revolutionslideshow';
		$this->_headerText = Mage::helper('revolutionslideshow')->__('Revolution Slides Manager');
		$this->_addButtonLabel = Mage::helper('revolutionslideshow')->__('Add Slide');
		parent::__construct();
	}
}