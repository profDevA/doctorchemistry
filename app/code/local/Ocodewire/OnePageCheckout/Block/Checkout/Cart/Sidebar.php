<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Block_Checkout_Cart_Sidebar extends Mage_Checkout_Block_Cart_Sidebar
{
    
    public function getCheckoutUrl()
    {
        if (!$this->helper('onepagecheckout')->isRewriteCheckoutLinksEnabled()){
            return parent::getCheckoutUrl();
        }
        return $this->getUrl('onepagecheckout', array('_secure'=>true));
    }
}
