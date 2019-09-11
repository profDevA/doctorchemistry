<?php

class Rokanthemes_Brandslider_Block_Adminhtml_Brandslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('brandslider_form', array('legend'=>Mage::helper('brandslider')->__('Item information')));
     
	         
        //if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'multiselect', array(
                'name' => 'store_ids[]',
                'label' => $this->__('Store View'),
                'required' => TRUE,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
            ));
        //}
		
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('brandslider')->__('Title'),
          'required'  => false,
          'name'      => 'title',
      ));
	  
	  $fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('brandslider')->__('Link'),
          'required'  => false,
          'name'      => 'link',
      ));

      $fieldset->addField('image', 'file', array(
          'label'     => Mage::helper('brandslider')->__('Image'),
          'required'  => false,
          'name'      => 'image',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('brandslider')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('brandslider')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('brandslider')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('brandslider')->__('Description'),
          'title'     => Mage::helper('brandslider')->__('Description'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getBrandsliderData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandsliderData());
          Mage::getSingleton('adminhtml/session')->setBrandsliderData(null);
      } elseif ( Mage::registry('brandslider_data') ) {
          $form->setValues(Mage::registry('brandslider_data')->getData());
      }
      return parent::_prepareForm();
  }
}