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
 * @package     Mage_Tag
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Tagged products controller
 *
 * @category   Mage
 * @package    Mage_Tag
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once 'Mage/Tag/controllers/ProductController.php';

class Rokanthemes_Layerednavigation_ProductController extends Mage_Tag_ProductController {

    public function listAction() {

          $layer_action = $this->getRequest()->getParam('layer_action');
        if (Mage::helper('layerednavigation/data')->isAjax() && $layer_action == 1) {
            $data = array();
            $tagId = $this->getRequest()->getParam('tagId');
            $tag = Mage::getModel('tag/tag')->load($tagId);
            if (!$tag->getId() || !$tag->isAvailableInStore()) {
                $this->_forward('404');
                return;
            }
            Mage::register('current_tag', $tag);
            
            $this->loadLayout();
            $this->_initLayoutMessages('checkout/session');
            $this->_initLayoutMessages('tag/session');
            $this->renderLayout();
            
            $layerLeft = $this->getLayout()->getBlock('tags_popular')->toHtml();
            $productlist = $this->getLayout()->getBlock('tag_products')->toHtml();
            $data['status'] = 1;
            $data['leftLayer'] = $layerLeft;
            $data['tagtoolbarjs'] = Mage::helper('layerednavigation/data')->getToolbarForTagProductJs();
            $data['productlist'] = $productlist;
            //Mage::log($data, null, 'catalogsearch.log');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($data));
            return;
            
            
        } else {
            $tagId = $this->getRequest()->getParam('tagId');
            $tag = Mage::getModel('tag/tag')->load($tagId);
            if (!$tag->getId() || !$tag->isAvailableInStore()) {
                $this->_forward('404');
                return;
            }
            Mage::register('current_tag', $tag);

            $this->loadLayout();
            $this->_initLayoutMessages('checkout/session');
            $this->_initLayoutMessages('tag/session');
            $this->renderLayout();
        }
    }

}
