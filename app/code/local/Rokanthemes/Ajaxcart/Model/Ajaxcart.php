<?php

class Rokanthemes_Ajaxcart_Model_Ajaxcart extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ajaxcart/ajaxcart');
    }
}