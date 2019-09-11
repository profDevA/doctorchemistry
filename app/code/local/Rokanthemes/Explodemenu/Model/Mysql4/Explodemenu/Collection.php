<?php

class Rokanthemes_Explodemenu_Model_Mysql4_Explodemenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('explodemenu/explodemenu');
    }
}