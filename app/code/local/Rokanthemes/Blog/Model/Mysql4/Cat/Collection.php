<?php

class Rokanthemes_Blog_Model_Mysql4_Cat_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('blog/cat');
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('identifier', 'title');
    }

    public function addCatFilter($catId)
    {
        if (!Mage::app()->isSingleStoreMode()) {
            $this
                ->getSelect()
                ->join(
                    array('cat_table' => $this->getTable('post_cat')),
                    'main_table.post_id = cat_table.post_id',
                    array()
                )
                ->where('cat_table.cat_id = ?', $catId)
            ;
        }
        return $this;
    }

    public function addStoreFilter($store)
    {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this
                ->getSelect()
                ->joinLeft(
                    array('store_table' => $this->getTable('cat_store')),
                    'main_table.cat_id = store_table.cat_id',
                    array()
                )
                ->where("store_table.store_id = 0 OR store_table.store_id = {$store} OR store_table.store_id IS NULL")
            ;
            return $this;
        }
        return $this;
    }

    public function addPostFilter($postId)
    {
        $this
            ->getSelect()
            ->join(
                array('cat_table' => $this->getTable('post_cat')), 'main_table.cat_id = cat_table.cat_id', array()
            )
            ->where('cat_table.post_id = ?', $postId)
        ;
        return $this;
    }
}