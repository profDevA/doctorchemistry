<?php

class Rokanthemes_Brandslider_Model_Mysql4_Brandslider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brandslider/brandslider');
    }
}