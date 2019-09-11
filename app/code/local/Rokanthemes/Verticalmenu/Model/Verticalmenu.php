<?php

class Rokanthemes_Verticalmenu_Model_Verticalmenu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('verticalmenu/verticalmenu');
    }
}