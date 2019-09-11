<?php
class Rokanthemes_Brandslider_Block_Adminhtml_Brandslider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_brandslider';
    $this->_blockGroup = 'brandslider';
    $this->_headerText = Mage::helper('brandslider')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('brandslider')->__('Add Item');
    parent::__construct();
  }
}