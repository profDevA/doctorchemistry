<?php

class Rokanthemes_Explodemenu_Model_Mysql4_Explodemenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the explodemenu_id refers to the key field in your database table.
        $this->_init('explodemenu/explodemenu', 'explodemenu_id');
    }
}