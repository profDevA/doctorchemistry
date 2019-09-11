<?php

class Rokanthemes_Verticalmenu_Block_Adminhtml_Verticalmenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('verticalmenu_form', array('legend'=>Mage::helper('verticalmenu')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('verticalmenu')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('verticalmenu')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('verticalmenu')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('verticalmenu')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('verticalmenu')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('verticalmenu')->__('Content'),
          'title'     => Mage::helper('verticalmenu')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getVerticalmenuData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getVerticalmenuData());
          Mage::getSingleton('adminhtml/session')->setVerticalmenuData(null);
      } elseif ( Mage::registry('verticalmenu_data') ) {
          $form->setValues(Mage::registry('verticalmenu_data')->getData());
      }
      return parent::_prepareForm();
  }
}