<?php
/**
 *
 * Package :- Robots
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Robots_Block_Adminhtml_Robots_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('robots_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('robots')->__('Robots Information'));
	}
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
		'label'     => Mage::helper('robots')->__('Robots Information'),
		'title'     => Mage::helper('robots')->__('Robots Information'),
		));
		return parent::_beforeToHtml();
	}
}
