<?php
class Rokanthemes_Bestsellerproduct_Block_Bestsellerproduct extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	
    protected function useFlatCatalogProduct()
    {
        return Mage::getStoreConfig('catalog/frontend/flat_catalog_product');
    }
	public function getConfig($app) 
	{
		$config = Mage::getStoreConfig('bestsellerproduct');
		if (isset($config['bestsellerproduct_config']) ) {
			$value = $config['bestsellerproduct_config'][$app];
			return $value;
		} else {
			throw new Exception($app.' value not set');
		}
	} 
	public function getProducts()
    {	
		$_rootcatID = Mage::app()->getStore()->getRootCategoryId();		
		$collection = Mage::getResourceModel('catalog/product_collection');
        $collection->addAttributeToSelect('*')->addStoreFilter()->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')->addAttributeToFilter('category_id', array('in' => $_rootcatID));
        $orderItems = Mage::getSingleton('core/resource')->getTableName('sales/order_item');
        $orderMain =  Mage::getSingleton('core/resource')->getTableName('sales/order');
        $collection->getSelect()
            ->join(array('items' => $orderItems), "items.product_id = e.entity_id", array('count' => 'SUM(items.qty_ordered)'))
            ->join(array('trus' => $orderMain), "items.order_id = trus.entity_id", array())
            ->group('e.entity_id')
            ->order('count DESC');

        // getNumProduct
        $collection->setPageSize($this->getConfig('qty'));
		
        $this->setProductCollection($collection);
    }
    public function getBestsellerproduct()     
    { 
        if (!$this->hasData('bestsellerproduct')) {
            $this->setData('bestsellerproduct', Mage::registry('bestsellerproduct'));
        }
        return $this->getData('bestsellerproduct');
    }
	function cut_string_bestsellerproduct($str,$num){ 
		if(strlen($str) <= $num) {
			return $string;
		}
		else {	
			if(strpos($str," ",$num) > $num){
				$new_space = strpos($str," ",$num);
				$new_string = substr($str,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($str,0,$num)."..";
			return $new_string;
		}
	}
}