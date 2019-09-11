<?php

class Rokanthemes_Explodemenu_Block_Adminhtml_Explodemenu_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'explodemenu';
        $this->_controller = 'adminhtml_explodemenu';
        
        $this->_updateButton('save', 'label', Mage::helper('explodemenu')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('explodemenu')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('explodemenu_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'explodemenu_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'explodemenu_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('explodemenu_data') && Mage::registry('explodemenu_data')->getId() ) {
            return Mage::helper('explodemenu')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('explodemenu_data')->getTitle()));
        } else {
            return Mage::helper('explodemenu')->__('Add Item');
        }
    }
}