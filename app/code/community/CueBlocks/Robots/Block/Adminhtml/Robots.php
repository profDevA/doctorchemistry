<?php
/**
 *
 * Package :- Robots
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Robots_Block_Adminhtml_Robots extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_robots';
		$this->_blockGroup = 'robots';
		$this->_headerText = Mage::helper('robots')->__('Robots Manager');
		$this->_addButtonLabel = Mage::helper('robots')->__('Add Robots Options');
		parent::__construct();
	}
	
}
