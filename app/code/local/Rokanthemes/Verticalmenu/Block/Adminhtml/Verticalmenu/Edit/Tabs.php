<?php

class Rokanthemes_Verticalmenu_Block_Adminhtml_Verticalmenu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('verticalmenu_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('verticalmenu')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('verticalmenu')->__('Item Information'),
          'title'     => Mage::helper('verticalmenu')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('verticalmenu/adminhtml_verticalmenu_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}