<?php

class Rokanthemes_Brandslider_Block_Adminhtml_Brandslider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'brandslider';
        $this->_controller = 'adminhtml_brandslider';
        
        $this->_updateButton('save', 'label', Mage::helper('brandslider')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('brandslider')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('brandslider_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'brandslider_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'brandslider_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('brandslider_data') && Mage::registry('brandslider_data')->getId() ) {
            return Mage::helper('brandslider')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('brandslider_data')->getTitle()));
        } else {
            return Mage::helper('brandslider')->__('Add Item');
        }
    }
}