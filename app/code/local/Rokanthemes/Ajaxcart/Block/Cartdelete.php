<?php
 
class Rokanthemes_Ajaxcart_Block_Cartdelete extends Mage_Catalog_Block_Product_Abstract
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ajaxcart/ajaxcart/ajaxcart.phtml');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getInviteButtonHtml()
    {
        return $this->getChildHtml('invite_button');
    }

    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}
