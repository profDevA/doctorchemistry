<?php

class Rokanthemes_Explodemenu_Block_Adminhtml_Explodemenu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('explodemenu_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('explodemenu')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('explodemenu')->__('Item Information'),
          'title'     => Mage::helper('explodemenu')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('explodemenu/adminhtml_explodemenu_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}