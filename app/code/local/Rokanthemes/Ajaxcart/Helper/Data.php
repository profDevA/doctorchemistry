<?php

class Rokanthemes_Ajaxcart_Helper_Data extends Mage_Core_Helper_Abstract
{
    //check if is ajax request
    public function isAjax() {
        return (boolean) ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
    
    public function getEmptyCartHtml() {
                $html = '<div class="col-main">
                            <div class="page-title">
                                <h1>'.$this->__("Shopping Cart is Empty").'</h1>
                            </div>
                            <div class="cart-empty">
                                        <p>'.$this->__("You have no items in your shopping cart").'.</p>
                                <p>'.$this->__("Click").'<a href="'.Mage::getBaseUrl().'">'.$this->__("here").'</a>'.$this->__("to continue shopping").'.</p>
                            </div>
                         </div>';
                return $html;
    }

    public function getContinueShoppingUrl()
    {
        $url = $this->getData('continue_shopping_url');
        if (is_null($url)) {
            $url = Mage::getSingleton('checkout/session')->getContinueShoppingUrl(true);
            if (!$url) {
                $url = Mage::getUrl();
            }
            $this->setData('continue_shopping_url', $url);
        }
        return $url;
    }
    
    public function productHtml($pname = NULL, $plink = NULL, $pimage = NULL) {
        $html = ""; 
        $html .="<div class ='p_image'><img src ='".$pimage."' /></div>";
        $html .= "<div class ='p_name'><a href ='".$plink."'>".$pname."</a></div>";
        return $html;
    }
	
	public function getTotalWishlist() {
		$customer = Mage::helper('customer')->getCustomer(); 
		
		$wishList = Mage::getSingleton('wishlist/wishlist')->loadByCustomer($customer);
		$wishListItemCollection = $wishList->getItemCollection();
		
		if (count($wishListItemCollection)) {
			$arrProductIds = array();

			foreach ($wishListItemCollection as $item) {
				/* @var $product Mage_Catalog_Model_Product */
				$product = $item->getProduct();
				$arrProductIds[] = $product->getId();
			}
		}
		return count($arrProductIds);   
	}	
}