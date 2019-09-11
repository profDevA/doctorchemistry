<?php


class Rokanthemes_Revolutionslideshow_Block_Adminhtml_Revolutionslideshow_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'revolutionslideshow';
        $this->_controller = 'adminhtml_revolutionslideshow';
        
        $this->_updateButton('save', 'label', Mage::helper('revolutionslideshow')->__('Save Slide'));
        $this->_updateButton('delete', 'label', Mage::helper('revolutionslideshow')->__('Delete Slide'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('revolutionslideshow_data') && Mage::registry('revolutionslideshow_data')->getId() ) {
            return Mage::helper('revolutionslideshow')->__("Edit Slide");
        } else {
            return Mage::helper('revolutionslideshow')->__('Add Slide');
        }
    }
}