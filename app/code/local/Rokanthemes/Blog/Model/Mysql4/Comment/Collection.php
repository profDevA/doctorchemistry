<?php

class Rokanthemes_Blog_Model_Mysql4_Comment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('blog/comment');
    }

    public function addApproveFilter($status)
    {
        $this->getSelect()
            ->where('status = ?', $status);
        return $this;
    }

    public function addPostFilter($postId)
    {
        $this->getSelect()
            ->where('post_id = ?', $postId);
        return $this;
    }
}