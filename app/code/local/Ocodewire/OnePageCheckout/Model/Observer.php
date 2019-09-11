<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Model_Observer extends Mage_Core_Model_Abstract
{
    public function initialize_checkout($observer)
    {
        $helper = Mage::helper('onepagecheckout/checkout');
    }
}
