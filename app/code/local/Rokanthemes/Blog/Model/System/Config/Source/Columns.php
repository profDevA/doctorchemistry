<?php

class Rokanthemes_Blog_Model_System_Config_Source_Columns
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Yes, all pages')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('Yes, only blog page')),
            array('value' => 0, 'label' => Mage::helper('adminhtml')->__('No')),
        );
    }
}