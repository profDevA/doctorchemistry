<?php

class Rokanthemes_Blog_Block_Menu_Sidebar extends Rokanthemes_Blog_Block_Abstract
{
    public function getRecent()
    {
        // widget declaration
        if ($this->getBlogWidgetRecentCount()) {
            $size = $this->getBlogWidgetRecentCount();
        } else {
            // standard output
            $size = self::$_helper->getRecentPage();
        }

        if ($size) {
            $collection = clone self::$_collection;
            $collection->setPageSize($size);

            foreach ($collection as $item) {
                $item->setAddress($this->getBlogUrl($item->getIdentifier()));
            }
            return $collection;
        }
        return false;
    }

    public function getCategories()
    {
        $collection = Mage::getModel('blog/cat')
            ->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('sort_order', 'asc')
        ;
        foreach ($collection as $item) {
            $item->setAddress($this->getBlogUrl(array(self::$_catUriParam, $item->getIdentifier())));
        } 
        return $collection;
    }
	
	public function getContentBlogSidebar($_description, $count) {
	   $short_desc = substr($_description, 0, $count);
	   if(substr($short_desc, 0, strrpos($short_desc, ' '))!='') {
			$short_desc = substr($short_desc, 0, strrpos($short_desc, ' '));
			$short_desc = $short_desc.'...';
		}
	   return $short_desc;
	}

    // protected function _beforeToHtml()
    // {
        // return $this;
    // }

    // protected function _toHtml()
    // {
        // if (self::$_helper->getEnabled()) {
            // $parent = $this->getParentBlock();
            // if (!$parent) {
                // return null;
            // }

            // $showLeft = Mage::getStoreConfig('blog/menu/left');
            // $showRight = Mage::getStoreConfig('blog/menu/right');

            // $isBlogPage = Mage::app()->getRequest()->getModuleName() == Rokanthemes_Blog_Helper_Data::DEFAULT_ROOT;

            // $leftAllowed = ($isBlogPage && ($showLeft == 2)) || ($showLeft == 1);
            // $rightAllowed = ($isBlogPage && ($showRight == 2)) || ($showRight == 1);

            // if (!$leftAllowed && ($parent->getNameInLayout() == 'left')) {
                // return null;
            // }
            // if (!$rightAllowed && ($parent->getNameInLayout() == 'right')) {
                // return null;
            // }

            // return parent::_toHtml();
        // }
    // }
}