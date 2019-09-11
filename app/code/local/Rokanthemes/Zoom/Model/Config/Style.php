<?php
/*------------------------------------------------------------------------
# APL Solutions and Vision Co., LTD
# ------------------------------------------------------------------------
# Copyright (C) 2008-2010 APL Solutions and Vision Co., LTD. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: APL Solutions and Vision Co., LTD
# Websites: http://www.joomlavision.com/ - http://www.magentheme.com/
-------------------------------------------------------------------------*/ 
class Rokanthemes_Zoom_Model_Config_Style
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'horizontal', 'label'=>Mage::helper('adminhtml')->__('Horizontal')),
            array('value'=>'vetical', 'label'=>Mage::helper('adminhtml')->__('Vetical')),
        );
    }

}
