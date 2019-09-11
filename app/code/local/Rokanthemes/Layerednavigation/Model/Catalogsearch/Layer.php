<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_CatalogSearch
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rokanthemes_Layerednavigation_Model_Catalogsearch_Layer extends Mage_CatalogSearch_Model_Layer {

    public function prepareProductCollection($collection) {
     
        $collection
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText())
                ->setStore(Mage::app()->getStore())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addUrlRewrite();

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);
      
        $limit =Mage::app()->getRequest()->getParam('limit');
	if(!$limit) { $limit = 9 ; }
        $collection->setPage(Mage::getBlockSingleton('page/html_pager')->getCurrentPage(),$limit) 
                    ->setOrder(Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentOrder(), Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection());
        $fisrt = NULL; 
        $last = NULL;
        if(isset($_GET['last'])) {
                $last=$_GET['last'];
        }
        if(isset($_GET['first'])) {
                $fisrt=$_GET['first'];	
        }
        if(isset( $_GET['rate'])){
            $rate = $_GET['rate'];
            $last = $last / $rate;
            $fisrt = $fisrt / $rate;
        }
        if($fisrt && $last){
            $collection
            ->addFieldToFilter('price', array('gteq'=>$fisrt))
            ->addFieldToFilter('price', array('lteq'=>$last));
        }
        
        return $this;
    }


    
}