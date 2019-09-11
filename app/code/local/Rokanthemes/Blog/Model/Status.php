<?php

class Rokanthemes_Blog_Model_Status extends Varien_Object
{
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 2;
    const STATUS_HIDDEN   = 3;

    public function addEnabledFilterToCollection($collection)
    {
        $collection->addEnableFilter(array('in' => $this->getEnabledStatusIds()));
        return $this;
    }

    public function addCatFilterToCollection($collection, $cat)
    {
        $collection->addCatFilter($cat);
        return $this;
    }

    public function getEnabledStatusIds()
    {
        return array(self::STATUS_ENABLED);
    }

    public function getDisabledStatusIds()
    {
        return array(self::STATUS_DISABLED);
    }

    public function getHiddenStatusIds()
    {
        return array(self::STATUS_HIDDEN);
    }

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED  => Mage::helper('blog')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('blog')->__('Disabled'),
            self::STATUS_HIDDEN   => Mage::helper('blog')->__('Hidden')
        );
    }
}