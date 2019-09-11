<?php
class Rokanthemes_Mostviewedproduct_Block_Mostviewedproduct extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getMostviewedproduct()     
    { 
        if (!$this->hasData('mostviewedproduct')) {
            $this->setData('mostviewedproduct', Mage::registry('mostviewedproduct'));
        }
        return $this->getData('mostviewedproduct');
    }
	public function getProducts()
    {
    	$storeId    = Mage::app()->getStore()->getId();
		$products = Mage::getResourceModel('reports/product_collection')
            ->addAttributeToSelect('*')
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()			
            ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->addViewsCount()
            ;			
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
        $this->setProductCollection($products);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('mostviewedproduct');
		if (isset($config['mostviewedproduct_config']) ) {
			$value = $config['mostviewedproduct_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
	
	function cut_string_mostviewedproduct($string,$number){
		if(strlen($string) <= $number) {
			return $string;
		}
		else {	
			if(strpos($string," ",$number) > $number){
				$new_space = strpos($string," ",$number);
				$new_string = substr($string,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($string,0,$number)."..";
			return $new_string;
		}
	}
}