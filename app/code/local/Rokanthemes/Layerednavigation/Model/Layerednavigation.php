<?php

class Rokanthemes_Layerednavigation_Model_Layerednavigation extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layerednavigation/layerednavigation');
    }
}