<?php
class Rokanthemes_Blog_Block_Tags extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        return $this->setTemplate('rokanthemes_blog/tags.phtml');
    }

    public function getCollection()
    {
        if (!$this->getData('collection')) {

            $collection = Mage::getModel('blog/tag')->getCollection()->getActiveTags();

            foreach ($collection as $item) {
                if ($item->getTagFinalCount() >= $this->getMaxCount()) {
                    $this->setMaxCount($item->getTagFinalCount());
                } elseif ($item->getTagFinalCount() <= $this->getMinCount() || !$this->getMinCount()) {
                    $this->setMinCount($item->getTagFinalCount());
                    $this->setMinTag($item);
                }
            }
            if ($collection->count()) {
                if (!$this->getMinTag()) {
                    $this->setMinTag($item);
                }
                if (!$this->getMaxTag()) {
                    $this->setMaxTag($item);
                }
            }

            $this->setData('collection', $collection);
        }

        return $this->getData('collection');
    }

    public function getTagWeight($tag, $isMin = null)
    {
        $maxWeight = $this->getMaxCount();

        $count = $tag->getTagFinalCount();

        if ($maxWeight) {
            $k = ($count / (intval($maxWeight)));
        } else {
            $k = 0.1;
        }

        if (!$isMin) {
            $weight = $this->getTagWeight($this->getMinTag(), 1);
            if ((int)$weight) {
                $k = $k / $weight;
            } else {
                $k = 0.1;
            }
        }

        return round($k * 10);
    }

    public function getTagHref($tag)
    {
        $route = Mage::helper('blog')->getRoute();
        return Mage::getUrl($route . "/tag/" . urlencode($tag->getTag()));
    }
}