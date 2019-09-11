<?php

/**
 * Effect options
 *
 */
class Rokanthemes_Verticalmenu_Adminhtml_Model_System_Config_Source_Effectoptions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('SlideDown')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('FadeIn')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Show')),
        );
    }

}