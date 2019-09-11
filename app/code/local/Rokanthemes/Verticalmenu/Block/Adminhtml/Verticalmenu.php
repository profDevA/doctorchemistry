<?php
class Rokanthemes_Verticalmenu_Block_Adminhtml_Verticalmenu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_verticalmenu';
    $this->_blockGroup = 'verticalmenu';
    $this->_headerText = Mage::helper('verticalmenu')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('verticalmenu')->__('Add Item');
    parent::__construct();
  }
}