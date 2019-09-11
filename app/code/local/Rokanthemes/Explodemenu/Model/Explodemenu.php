<?php

class Rokanthemes_Explodemenu_Model_Explodemenu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('explodemenu/explodemenu');
    }
}