<?php

class Rokanthemes_Explodemenu_Block_Adminhtml_Explodemenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('explodemenu_form', array('legend'=>Mage::helper('explodemenu')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('explodemenu')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('explodemenu')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('explodemenu')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('explodemenu')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('explodemenu')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('explodemenu')->__('Content'),
          'title'     => Mage::helper('explodemenu')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getExplodemenuData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getExplodemenuData());
          Mage::getSingleton('adminhtml/session')->setExplodemenuData(null);
      } elseif ( Mage::registry('explodemenu_data') ) {
          $form->setValues(Mage::registry('explodemenu_data')->getData());
      }
      return parent::_prepareForm();
  }
}