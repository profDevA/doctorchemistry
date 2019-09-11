<?php

class Rokanthemes_Blog_Model_Mysql4_Tag_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_previewFlag;

    protected function _construct()
    {
        $this->_init('blog/tag');
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('identifier', 'title');
    }

    public function addStoreFilter($store)
    {
        if (!Mage::app()->isSingleStoreMode()) {
            $id = $store->getId();
            $this->getSelect()->where('store_id=' . $id . ' OR store_id=0');
            return $this;
        }
        return $this;
    }

    public function addTagFilter($tag)
    {
        $this->getSelect()->where('tag=?', $tag);
        return $this;
    }

    public function getActiveTags()
    {
        $this->getSelect()
            ->columns(array('tag_final_count' => 'SUM(tag_count)'))
            ->joinLeft(
                array("stores" => $this->getTable('blog/store')), 'main_table.store_id = stores.store_id', array()
            )
            ->joinLeft(array("blogs" => $this->getTable('blog/blog')), "stores.post_id = blogs.post_id", array())
            ->where('blogs.status = 1')
            ->where('tag_count > 0')
            ->where('FIND_IN_SET(main_table.tag, blogs.tags)')
            ->where('main_table.store_id = ? OR main_table.store_id = 0', Mage::app()->getStore()->getId())
            ->order(array('tag_final_count DESC', 'tag'))
            ->limit(Mage::getStoreConfig(Rokanthemes_Blog_Helper_Config::XML_TAGCLOUD_SIZE))
            ->group('tag')
        ;

        return $this;
    }
}