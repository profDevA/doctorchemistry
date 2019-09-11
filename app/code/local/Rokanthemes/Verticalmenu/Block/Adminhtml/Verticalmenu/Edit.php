<?php

class Rokanthemes_Verticalmenu_Block_Adminhtml_Verticalmenu_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'verticalmenu';
        $this->_controller = 'adminhtml_verticalmenu';
        
        $this->_updateButton('save', 'label', Mage::helper('verticalmenu')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('verticalmenu')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('verticalmenu_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'verticalmenu_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'verticalmenu_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('verticalmenu_data') && Mage::registry('verticalmenu_data')->getId() ) {
            return Mage::helper('verticalmenu')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('verticalmenu_data')->getTitle()));
        } else {
            return Mage::helper('verticalmenu')->__('Add Item');
        }
    }
}