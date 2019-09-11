<?php

class Rokanthemes_Brandslider_Block_Adminhtml_Brandslider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('brandslider_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('brandslider')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('brandslider')->__('Item Information'),
          'title'     => Mage::helper('brandslider')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('brandslider/adminhtml_brandslider_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}