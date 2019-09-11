<?php
class Rokanthemes_Explodemenu_Block_Adminhtml_Explodemenu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_explodemenu';
    $this->_blockGroup = 'explodemenu';
    $this->_headerText = Mage::helper('explodemenu')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('explodemenu')->__('Add Item');
    parent::__construct();
  }
}