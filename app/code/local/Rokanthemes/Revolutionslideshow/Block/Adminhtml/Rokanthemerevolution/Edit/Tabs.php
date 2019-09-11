<?php


class Rokanthemes_Revolutionslideshow_Block_Adminhtml_Rokanthemerevolution_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('rokanthemerevolution_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('revolutionslideshow')->__('Revolution Slide Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('revolutionslideshow')->__('Revolution Slide Information'),
          'title'     => Mage::helper('revolutionslideshow')->__('Revolution Slide Information'),
          'content'   => $this->getLayout()->createBlock('revolutionslideshow/adminhtml_rokanthemerevolution_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}