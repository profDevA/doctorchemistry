<?php

class Rokanthemes_Blog_Model_Api extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/blog');
    }

    public function getPostUrl($id)
    {
        $post = $this->load($id);
        if ($post->getId()) {
            $route = Mage::helper('blog')->getRoute();
            return Mage::getUrl("{$route}/{$post->getIdentifier()}");
        }
        return false;
    }

    public function getPostCategories($id)
    {
        return Mage::getModel('blog/cat')
            ->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addPostFilter($id)
        ;
    }

    public function getPosts($status = array(), $store = array())
    {
        $collection = Mage::getModel('blog/post')->getCollection();
        if (is_array($store) && !empty($store)) {
            $collection->addStoreFilter($store);
        }
        if (!empty($status)) {
            $collection->addStatusFilter($status);
        } else {
            $collection->addStatusFilter();
        }
        return $collection;
    }

    public function getPostShortContent($post, $storeId = 0)
    {
        $content = $post->getPostContent();
        $isUseShortContent = Mage::getStoreConfig(Rokanthemes_Blog_Helper_Config::XML_BLOG_USESHORTCONTENT, $storeId);
        if ($isUseShortContent && trim($post->getShortContent())) {
            $content = trim($post->getShortContent());
        } elseif ((int)Mage::getStoreConfig(Rokanthemes_Blog_Helper_Config::XML_BLOG_READMORE, $storeId)) {
            $strManager = new Rokanthemes_Blog_Helper_Substring(
                array(
                     'input' => Mage::helper('blog')->filterWYS($post->getPostContent())
                )
            );
            $content = $strManager->getHtmlSubstr((int)Mage::getStoreConfig(Rokanthemes_Blog_Helper_Config::XML_BLOG_READMORE));
        }
        return $content;
    }
}