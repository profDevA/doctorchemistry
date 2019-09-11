<?php
class Rokanthemes_Newproduct_Block_Newproduct extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getNewproduct()     
    { 
        if (!$this->hasData('newproduct')) {
            $this->setData('newproduct', Mage::registry('newproduct'));
        }
        return $this->getData('newproduct');
    }
	public function getProducts()
    {
		$_rootcatID = Mage::app()->getStore()->getRootCategoryId();
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	$storeId    = Mage::app()->getStore()->getId();
		$products = Mage::getResourceModel('catalog/product_collection')
			->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addStoreFilter()
			->addAttributeToFilter('category_id', array('in' => $_rootcatID))
			->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
			->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
			->addAttributeToSort('news_from_date','desc');		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
        $this->setProductCollection($products);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('newproduct');
		if (isset($config['newproduct_config']) ) {
			$value = $config['newproduct_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
	
	function cut_string_newproduct($string,$number){
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