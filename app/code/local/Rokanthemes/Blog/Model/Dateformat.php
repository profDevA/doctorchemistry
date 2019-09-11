<?php

class Rokanthemes_Blog_Model_Dateformat
{
    const FORMAT_TYPE_FULL   = 'full';
    const FORMAT_TYPE_LONG   = 'long';
    const FORMAT_TYPE_MEDIUM = 'medium';
    const FORMAT_TYPE_SHORT  = 'short';

    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options[] = array(
                'value' => self::FORMAT_TYPE_FULL,
                'label' => Mage::helper('blog')->__('Full')
            );
            $this->_options[] = array(
                'value' => self::FORMAT_TYPE_LONG,
                'label' => Mage::helper('blog')->__('Long')
            );
            $this->_options[] = array(
                'value' => self::FORMAT_TYPE_MEDIUM,
                'label' => Mage::helper('blog')->__('Medium')
            );
            $this->_options[] = array(
                'value' => self::FORMAT_TYPE_SHORT,
                'label' => Mage::helper('blog')->__('Short')
            );
        }
        return $this->_options;
    }
}