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
/**
 * Catalog Search Controller
 */
require_once 'Mage/CatalogSearch/controllers/ResultController.php';

class Rokanthemes_Layerednavigation_ResultController extends Mage_CatalogSearch_ResultController {

    /**
     * Display search result
     */
    public function indexAction() {
                
         $layer_action = $this->getRequest()->getParam('layer_action');
        if (Mage::helper('layerednavigation/data')->isAjax() && $layer_action == 1) {
            $data = array();
            $query = Mage::helper('catalogsearch')->getQuery();
            /* @var $query Mage_CatalogSearch_Model_Query */

            $query->setStoreId(Mage::app()->getStore()->getId());

            if ($query->getQueryText() != '') {
                if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                    $query->setId(0)
                            ->setIsActive(1)
                            ->setIsProcessed(1);
                } else {
                    if ($query->getId()) {
                        $query->setPopularity($query->getPopularity() + 1);
                    } else {
                        $query->setPopularity(1);
                    }

                    if ($query->getRedirect()) {
                        $query->save();
                        $this->getResponse()->setRedirect($query->getRedirect());
                        return;
                    } else {
                        $query->prepare();
                    }
                }

                Mage::helper('catalogsearch')->checkNotes();

                $this->loadLayout();
                $this->_initLayoutMessages('catalog/session');
                $this->_initLayoutMessages('checkout/session');
                $this->renderLayout();

                if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                    $query->save();
                }
                $layerLeft = $this->getLayout()->getBlock('catalogsearch.leftnav')->toHtml(); 
                $productlist = $this->getLayout()->getBlock('search_result_list')->toHtml(); 
                $pcount = $this->getLayout()
                            ->getBlockSingleton('catalog/product_list')
                            ->getLoadedProductCollection();
                $data['status'] = 1;
                $data['leftLayer'] = $layerLeft;
                $data['removeItem'] = Mage::helper('layerednavigation/data')->getJsRemoveItem();
                $data['productlist'] = $productlist;
                $data['pcount'] = count($pcount);
                //Mage::log($data, null, 'catalogsearch.log');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($data));
                return;
            }
        } else {
            $query = Mage::helper('catalogsearch')->getQuery();
            /* @var $query Mage_CatalogSearch_Model_Query */

            $query->setStoreId(Mage::app()->getStore()->getId());

            if ($query->getQueryText() != '') {
                if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                    $query->setId(0)
                            ->setIsActive(1)
                            ->setIsProcessed(1);
                } else {
                    if ($query->getId()) {
                        $query->setPopularity($query->getPopularity() + 1);
                    } else {
                        $query->setPopularity(1);
                    }

                    if ($query->getRedirect()) {
                        $query->save();
                        $this->getResponse()->setRedirect($query->getRedirect());
                        return;
                    } else {
                        $query->prepare();
                    }
                }

                Mage::helper('catalogsearch')->checkNotes();

                $this->loadLayout();
                $this->_initLayoutMessages('catalog/session');
                $this->_initLayoutMessages('checkout/session');
                $this->renderLayout();

                if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                    $query->save();
                }
            } else {
                $this->_redirectReferer();
            }
        }
    }

}
