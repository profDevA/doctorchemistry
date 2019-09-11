<?php

class Rokanthemes_Verticalmenu_Model_Mysql4_Verticalmenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('verticalmenu/verticalmenu');
    }
}