<?php

class Rokanthemes_Blog_Model_Comment extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('blog/comment');
    }

    public function load($id, $field = null)
    {
        return parent::load($id, $field);
    }
}