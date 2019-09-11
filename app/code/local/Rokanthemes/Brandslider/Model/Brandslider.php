<?php

class Rokanthemes_Brandslider_Model_Brandslider extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brandslider/brandslider');
    }
}