<?php

class Rokanthemes_Blog_Model_Mysql4_Related extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('blog/related', 'post_id');
    }

    public function load(Mage_Core_Model_Abstract $object, $value, $field = null)
    {
        if (strcmp($value, (int)$value) !== 0) {
            $field = 'post_id';
        }
        return parent::load($object, $value, $field);
    }
}