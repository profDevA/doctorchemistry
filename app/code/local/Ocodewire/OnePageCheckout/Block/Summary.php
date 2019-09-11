<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Block_Summary extends Mage_Checkout_Block_Cart_Totals {

    public function __construct()
    {
        $this->getQuote()->collectTotals()->save();
    }

    public function getItems()
    {
        return $this->getQuote()->getAllVisibleItems();
    }

    public function getTotals()
    {
        return $this->getQuote()->getTotals();
    }

    public function getGrandTotal(){
        return $this->getQuote()->getGrandTotal();
    }
}
