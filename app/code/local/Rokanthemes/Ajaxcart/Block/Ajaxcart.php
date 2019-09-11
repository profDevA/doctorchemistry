<?php
class Rokanthemes_Ajaxcart_Block_Ajaxcart extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAjaxcart()     
     { 
        if (!$this->hasData('ajaxcart')) {
            $this->setData('ajaxcart', Mage::registry('ajaxcart'));
        }
        return $this->getData('ajaxcart');
        
    }
}