<?php

class Rokanthemes_Blog_Model_Cat extends Mage_Core_Model_Abstract
{
    const NOROUTE_PAGE_ID = 'no-route';

    protected function _construct()
    {
        $this->_init('blog/cat');
    }

    public function load($id, $field = null)
    {
        return parent::load($id, $field);
    }

    public function noRoutePage()
    {
        $this->setData($this->load(self::NOROUTE_PAGE_ID, $this->getIdFieldName()));
        return $this;
    }

    public function getShortContent()
    {
        $content = $this->getData('short_content');
        if (Mage::getStoreConfig(Rokanthemes_Blog_Helper_Config::XML_BLOG_PARSE_CMS)) {
            $processor = Mage::getModel('core/email_template_filter');
            $content = $processor->filter($content);
        }
        return $content;
    }

    public function getPostContent()
    {
        $content = $this->getData('post_content');
        if (Mage::getStoreConfig(Rokanthemes_Blog_Helper_Config::XML_BLOG_PARSE_CMS)) {
            $processor = Mage::getModel('core/email_template_filter');
            $content = $processor->filter($content);
        }
        return $content;
    }
}