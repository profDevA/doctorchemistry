<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Block_Checkout_Onepage_Link extends Mage_Checkout_Block_Onepage_Link
{
    public function getCheckoutUrl()
    {
       
        return $this->getUrl('onepagecheckout', array('_secure'=>true));
    }
}
