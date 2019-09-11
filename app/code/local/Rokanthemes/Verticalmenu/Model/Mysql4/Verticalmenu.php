<?php

class Rokanthemes_Verticalmenu_Model_Mysql4_Verticalmenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the vmegamenu_id refers to the key field in your database table.
        $this->_init('verticalmenu/verticalmenu', 'verticalmenu_id');
    }
}