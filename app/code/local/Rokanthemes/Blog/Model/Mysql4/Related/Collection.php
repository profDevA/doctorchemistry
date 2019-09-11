<?php

class Rokanthemes_Blog_Model_Mysql4_Related_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('blog/related');
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('post_id', 'product_id');
    }

    public function addPostFilter($postId)
    {
        $this->getSelect()
            ->where('post_id = ?', $postId);
        return $this;
    }
}