<?php


class Rokanthemes_Athleteslideshow_Block_Adminhtml_Athleteslideshow_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('revolutionslideshow_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('revolutionslideshow')->__('Athlete Slide Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('revolutionslideshow')->__('Athlete Slide Information'),
          'title'     => Mage::helper('revolutionslideshow')->__('Athlete Slide Information'),
          'content'   => $this->getLayout()->createBlock('revolutionslideshow/adminhtml_revolutionslideshow_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}