<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Block_Checkout_Links extends Mage_Checkout_Block_Links
{
   
    public function addCheckoutLink()
    {

        if (!$this->helper('checkout')->canOnepageCheckout()) {
            return $this;
        }
        if ($parentBlock = $this->getParentBlock()) {
            $text = $this->__('Checkout');
            $parentBlock->addLink($text, 'onepagecheckout', $text, true, array('_secure'=>true), 60, null, 'class="top-link-onepagecheckout"');
        }
        return $this;
    }
}
