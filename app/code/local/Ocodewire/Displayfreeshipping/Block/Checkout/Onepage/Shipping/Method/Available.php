<?php
 
class Ocodewire_Displayfreeshipping_Block_Checkout_Onepage_Shipping_Method_Available extends Mage_Checkout_Block_Onepage_Shipping_Method_Available {
 
    public function getShippingRates() {
        $groups = parent::getShippingRates();
        $free = array();
        foreach ($groups as $code => $_rates) {
            foreach ($_rates as $_rate) {
                if (!$_rate->getPrice() > 0) {
                    $free[$code] = $_rates;
                }
            }
        }
        if (!empty($free)) {
            return $this->_rates = $free;
        }
        return $groups;
    }
 
}
