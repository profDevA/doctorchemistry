<?php

class Rokanthemes_Blog_Model_Related extends Mage_Core_Model_Abstract
{
    const NOROUTE_PAGE_ID = 'no-route';

    protected function _construct()
    {
        $this->_init('blog/related');
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
}