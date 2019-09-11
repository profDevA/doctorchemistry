<?php

class Rokanthemes_Brandslider_Model_Mysql4_Brandslider extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the brandslider_id refers to the key field in your database table.
        $this->_init('brandslider/brandslider', 'brandslider_id');
    }
}