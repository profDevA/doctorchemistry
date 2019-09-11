<?php
require_once 'Mage/Wishlist/controllers/IndexController.php';
class Rokanthemes_Ajaxcart_Wishlist_IndexController extends Mage_Wishlist_IndexController
{
    /**
     * ovveride Adding new item
     */
    public function addAction()
    {   
        //Mage::log(Mage::getSingleton('customer/session')->isLoggedIn(), null,'wishlist.log');
      if ($this->getRequest()->getParam('callback')) {
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            return $this->norouteAction();
        }

        $session = Mage::getSingleton('customer/session');
      
        $productId = (int) $this->getRequest()->getParam('product');
        if (!$productId) {
            $this->_redirect('*/');
            return;
        }

        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId() || !$product->isVisibleInCatalog()) {
            $session->addError($this->__('Cannot specify product.'));
            $this->_redirect('*/');
            return;
        }

        try {
            $requestParams = $this->getRequest()->getParams();
            if ($session->getBeforeWishlistRequest()) {
                $requestParams = $session->getBeforeWishlistRequest();
                $session->unsBeforeWishlistRequest();
            }
            $buyRequest = new Varien_Object($requestParams);

            $result = $wishlist->addNewItem($product, $buyRequest);
            if (is_string($result)) {
                Mage::throwException($result);
            }
            $wishlist->save();

            Mage::dispatchEvent(
                'wishlist_add_product',
                array(
                    'wishlist'  => $wishlist,
                    'product'   => $product,
                    'item'      => $result
                )
            );

            $referer = $session->getBeforeWishlistUrl();
            if ($referer) {
                $session->setBeforeWishlistUrl(null);
            } else {
                $referer = $this->_getRefererUrl();
            }

            /**
             *  Set referer to avoid referring to the compare popup window
             */
            $session->setAddActionReferer($referer);

            Mage::helper('wishlist')->calculate();

            $message = $this->__('%1$s has been added to your wishlist. Click <a href="%2$s">here</a> to continue shopping.', $product->getName(), Mage::helper('core')->escapeUrl($referer));
            //$session->addSuccess($message);
        }
        catch (Mage_Core_Exception $e) {
            $session->addError($this->__('An error occurred while adding item to wishlist: %s', $e->getMessage()));
        }
        catch (Exception $e) {
            $session->addError($this->__('An error occurred while adding item to wishlist.'));
        }
        $this->loadLayout();
        $sidebarWishlist = "";
        if($this->getLayout()->getBlock('wishlist_sidebar')){
            $sidebarWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
        }
		$toplink = "";
        if($this->getLayout()->getBlock('top.links')){
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
        }
		$toplink2 = "";
		if($this->getLayout()->getBlock('top_wishlist_sidebar')) {
		   $toplink2 = $this->getLayout()->getBlock('top_wishlist_sidebar')->toHtml();
        }
        $ajaxData['status'] = 1;
        $ajaxData['wishlist_sidebar'] = $sidebarWishlist;
        $ajaxData['type_sidebar'] = 'wishlist';
        $ajaxData['top_link'] = $toplink;
		$ajaxData['toplink2'] = $toplink2;
        if (Mage::getStoreConfig('ajaxcart/ajaxcart_config/show_confirm')) {
			$pimage = Mage::helper('catalog/image')->init($product, 'small_image')->resize(55);
			$ajaxData['product_info'] = Mage::helper('ajaxcart/data')->productHtml($product->getName(), $product->getProductUrl(), $pimage);
		}
        $this->getResponse()->setBody($this->getRequest()->getParam('callback').'('.Mage::helper('core')->jsonEncode($ajaxData).')');
		
        return;
 
      }else {
          parent::addAction();
      }
    }
    
    
      /**
     * Override Remove item
     */
    public function removeAction()
    {
        
          if ($this->getRequest()->getParam('callback')) {
            $ajaxData = array();
            $id = (int) $this->getRequest()->getParam('item');
            $item = Mage::getModel('wishlist/item')->load($id);
            if (!$item->getId()) {
                return $this->norouteAction();
            }
            $wishlist = $this->_getWishlist($item->getWishlistId());
            if (!$wishlist) {
                return $this->norouteAction();
            }
            try {
                $item->delete();
                $wishlist->save();
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('customer/session')->addError(
                        $this->__('An error occurred while deleting the item from wishlist: %s', $e->getMessage())
                );
            } catch (Exception $e) {
                Mage::getSingleton('customer/session')->addError(
                        $this->__('An error occurred while deleting the item from wishlist.')
                );
            }

            Mage::helper('wishlist')->calculate();
            $this->loadLayout();
            $sidebarWishlist = "";
            if ($this->getLayout()->getBlock('wishlist_sidebar')) {
                $sidebarWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
            }
            if($this->getLayout()->getBlock('top.links')){
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
            }
			$toplink2 = "";
			if($this->getLayout()->getBlock('top_wishlist_sidebar')) {
			   $toplink2 = $this->getLayout()->getBlock('top_wishlist_sidebar')->toHtml();
			}
            $ajaxData['status'] = 1;
            $ajaxData['wishlist_sidebar'] = $sidebarWishlist;
            $ajaxData['type_sidebar'] = 'wishlist';
            $ajaxData['top_link'] = $toplink;
			$ajaxData['toplink2'] = $toplink2;
            $this->getResponse()->setBody($this->getRequest()->getParam('callback').'('.Mage::helper('core')->jsonEncode($ajaxData).')');
            return;
        } else {
            parent::removeAction();
        }
  
    }
    
    
        /**
     * Add wishlist item to shopping cart and remove from wishlist
     *
     * If Product has required options - item removed from wishlist and redirect
     * to product view page with message about needed defined required options
     */
    public function cartAction()
    {
      if ($this->getRequest()->getParam('callback')) {
        $ajaxData = array();
        $itemId = (int) $this->getRequest()->getParam('item');

        /* @var $item Mage_Wishlist_Model_Item */
        $item = Mage::getModel('wishlist/item')->load($itemId);
        if (!$item->getId()) {
          
            $ajaxData['status'] = 0;
            $ajaxData['url_wislist'] = Mage::getBaseUrl() . 'wishlist/index/configure/id/' . $itemId;
            $ajaxData['message'] = $this->__('This product no existed in wishlist');
            $this->getResponse()->setBody($this->getRequest()->getParam('callback').'('.Mage::helper('core')->jsonEncode($ajaxData).')');
            return;
            exit;
        }
        $wishlist = $this->_getWishlist($item->getWishlistId());
        if (!$wishlist) {
            return $this->_redirect('*/*');
        }

        // Set qty
        $qty = $this->getRequest()->getParam('qty');
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }
        $qty = $this->_processLocalizedQty($qty);
        if ($qty) {
            $item->setQty($qty);
        }

        /* @var $session Mage_Wishlist_Model_Session */
        $session    = Mage::getSingleton('wishlist/session');
        $cart       = Mage::getSingleton('checkout/cart');

        $redirectUrl = Mage::getUrl('*/*');

        try {
            $options = Mage::getModel('wishlist/item_option')->getCollection()
                    ->addItemFilter(array($itemId));
            $item->setOptions($options->getOptionsByItem($itemId));

            $buyRequest = Mage::helper('catalog/product')->addParamsToBuyRequest(
                $this->getRequest()->getParams(),
                array('current_config' => $item->getBuyRequest())
            );

            $item->mergeBuyRequest($buyRequest);
            $item->addToCart($cart, true);
            $cart->save()->getQuote()->collectTotals();
            $wishlist->save();

            Mage::helper('wishlist')->calculate();

            if (Mage::helper('checkout/cart')->getShouldRedirectToCart()) {
                $redirectUrl = Mage::helper('checkout/cart')->getCartUrl();
            } else if ($this->_getRefererUrl()) {
                $redirectUrl = $this->_getRefererUrl();
            }
            Mage::helper('wishlist')->calculate();
        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                //$session->addError(Mage::helper('wishlist')->__('This product(s) is currently out of stock'));
            } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
               // Mage::getSingleton('catalog/session')->addNotice($e->getMessage());
                $redirectUrl = Mage::getUrl('*/*/configure/', array('id' => $item->getId()));
            } else {
                Mage::getSingleton('catalog/session')->addNotice($e->getMessage());
                $redirectUrl = Mage::getUrl('*/*/configure/', array('id' => $item->getId()));
            }
            $ajaxData['status'] = 0;
            $ajaxData['url_wislist'] = $redirectUrl;
            $ajaxData['message'] = $this->__('Please chose product options');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($ajaxData));
            return;
            exit;
        } catch (Exception $e) {
            
           // $session->addException($e, Mage::helper('wishlist')->__('Cannot add item to shopping cart'));
            $ajaxData['status'] = 0;
            $ajaxData['url_wislist'] = $redirectUrl;
            $ajaxData['message'] = Mage::helper('wishlist')->__('Cannot add item to shopping cart');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($ajaxData));
            return;
            exit;
        }
        Mage::helper('wishlist')->calculate();
        $this->loadLayout();
        $sidebarWishlist = "";
        if ($this->getLayout()->getBlock('wishlist_sidebar')) {
            $sidebarWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
        }
        $toplink = "";
        if($this->getLayout()->getBlock('top.links')) {
            $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
        }
        $sidebarCart = "";
        if ($this->getLayout()->getBlock('cart_sidebar')) {
            $sidebarCart = $this->getLayout()->getBlock('cart_sidebar')->toHtml();
        }
        $mini_cart = "";
        if ($this->getLayout()->getBlock('cart_sidebar_mini')) {
            $mini_cart = $this->getLayout()->getBlock('cart_sidebar_mini')->toHtml();
        }
        $ajaxData['status'] = 1;
        $ajaxData['wishlist_sidebar'] = $sidebarWishlist;
        $ajaxData['top_link'] = $toplink;
        $ajaxData['type_sidebar'] = 'wishlist';
        $ajaxData['sidebar_cart'] = $sidebarCart;
        $ajaxData['mini_cart'] = $mini_cart;
//        $product = Mage::getModel('catalog/product')->load($item->getProductId());
//        $pimage = Mage::helper('catalog/image')->init($product, 'small_image')->resize(55);
//        $ajaxData['product_info'] = Mage::helper('ajaxcart/data')->productHtml($product->getName(), $product->getProductUrl(), $pimage);
        $this->getResponse()->setBody($this->getRequest()->getParam('callback').'('.Mage::helper('core')->jsonEncode($ajaxData).')');

      } else {
          parent::cartAction();
      }
    }


}