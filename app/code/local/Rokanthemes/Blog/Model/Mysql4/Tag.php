<?php

class Rokanthemes_Blog_Model_Mysql4_Tag extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('blog/tag', 'id');
    }
}