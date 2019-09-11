<?php
class Rokanthemes_Layerednavigation_Block_Catalog_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price {

    public $_currentCategory;
    public $_productCollection;
    public $_currMinPrice;
    public $_currMaxPrice;
    public $_searchSession;

    public function __construct() {

        $this->_currentCategory = Mage::registry('current_category');
        $this->_searchSession = Mage::getSingleton('catalogsearch/session');
        $this->setProductCollection();
        $this->setCurrentPrices();
        parent::__construct();
        if(Mage::getStoreConfig('layerednavigation/layerfiler_config/enabled')){
           $this->setTemplate('rokanthemes/layerednavigation/filter.phtml');
        }
        //Mage::getConfig()->getModuleConfig('Rokanthemes_Layerednavigation')->is('active', 'true');
    }

    public function getMaxRangePrice() {

        if ((isset($_GET['q']) && !isset($_GET['last'])) || !isset($_GET['q'])) {
            $direction = Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
             if($direction == 'asc') {
                $maxPrice = $this->_productCollection
                        ->getLastItem()
                        ->getFinalPrice();
             } else {
                $maxPrice = $this->_productCollection
                        ->getFirstItem()
                        ->getFinalPrice();
             }
        } else {
            $maxPrice = $this->_searchSession->getMaxPrice();
        }

        return $maxPrice;
    }

    public function getMinRangePrice() {

        if ((isset($_GET['q']) && !isset($_GET['first'])) || !isset($_GET['q'])) {
             $direction = Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
             if($direction == 'asc') {
                $minPrice = $this->_productCollection
                        ->getFirstItem()
                        ->getFinalPrice();
             } else {
                  $minPrice = $this->_productCollection
                        ->getLastItem()
                        ->getFinalPrice();
             }
            
        } else {
            $minPrice = $this->_searchSession->getMinPrice();
        }
        return $minPrice;
    }

    public function setProductCollection() {
        $direction = Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
        if ($this->_currentCategory) {
            $this->_productCollection = $this->_currentCategory
                    ->getProductCollection()
                    ->addAttributeToSelect('*')
                    ->setOrder('price', $direction);
			  Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
			  Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_productCollection);
        } else {
            $this->_productCollection = Mage::getSingleton('catalogsearch/layer')
                    ->getProductCollection()
                    ->addAttributeToSelect('*')
                    ->setOrder('price', $direction);
		  Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
       	  Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_productCollection);
        }
    }

    public function setCurrentPrices() {
        $rate = NULL;
        $direction = Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection();
        if(isset($_GET['rate'])){
            $rate = $this->getRequest()->getParam('rate'); 
        }
        $this->_currMinPrice = $this->getRequest()->getParam('first');
        $this->_currMaxPrice = $this->getRequest()->getParam('last');

        if (!$this->_currMaxPrice) {
            $curMax = $this->getMaxRangePrice();
            if($rate) {
                $curMax = $curMax/$rate;
            }
            
            $this->_currMaxPrice = $curMax;
        }
        
        if ((isset($_GET['q']) && !isset($_GET['first'])) || !isset($_GET['q'])) {
            if($direction == 'asc') {
                $searchMinPrice = $this->_productCollection->getFirstItem()->getFinalPrice();
            } else {
                $searchMinPrice = $this->_productCollection->getLastItem()->getFinalPrice();
            }
            if($rate) {
                $searchMinPrice = $searchMinPrice/$rate;
            }
            $this->_searchSession->setMinPrice($searchMinPrice);
        }


        if (!$this->_currMinPrice) {
            $curMin = $this->getMinRangePrice();
            if($rate) {
                $curMin = $curMin/$rate;    
            }
            $this->_currMinPrice = $curMin;
        }
        
        if ((isset($_GET['q']) && !isset($_GET['last'])) || !isset($_GET['q'])) {
            if($direction == 'asc') {
                $searchMaxPrice = $this->_productCollection->getLastItem()->getFinalPrice();
            } else {
                $searchMaxPrice = $this->_productCollection->getFirstItem()->getFinalPrice();
            }
            if($rate) {
                $searchMaxPrice = $searchMaxPrice/$rate;
            }
            $this->_searchSession->setMaxPrice($searchMaxPrice);
        }
    }

}
