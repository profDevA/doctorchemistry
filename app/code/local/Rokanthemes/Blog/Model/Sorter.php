<?php

class Rokanthemes_Blog_Model_Sorter
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Varien_Data_Collection::SORT_ORDER_DESC,
                'label' => Mage::helper('adminhtml')->__('Newest first'),
            ),
            array(
                'value' => Varien_Data_Collection::SORT_ORDER_ASC,
                'label' => Mage::helper('adminhtml')->__('Older first'),
            ),
        );
    }
}