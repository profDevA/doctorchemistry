<?php

/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Model_Observers_PresetDefaults extends Mage_Core_Model_Abstract {
    public $defaultFields = array('country_id', 'region', 'region_id', 'city', 'postcode');

    protected $_rates = array();

    protected $_paymentMethodsBlock = null;

    protected $_methods = array();

    public function setDefaults(Varien_Event_Observer $observer) {

        $quote = $observer->getEvent()->getQuote();

        if(Mage::getStoreConfig('onepagecheckout/general/rewrite_checkout_links', $quote->getStore())) {
            $this->callDefaults($observer);

        }
        
        
        // if(Mage::getStoreConfig('onepagecheckout/general/include_js_valid', $quote->getStore())) {
            //$this->callDefaults($observer);

       // }

        return $this;
    }

    
    public function callDefaults (Varien_Event_Observer $observer) {

        $this->setAddressDefaults($observer);
        $this->setShippingDefaults($observer);
        $this->setPaymentDefaults($observer);

        return $this;
    }

    public function setDefaultsOnLogin(Varien_Event_Observer $observer) {

        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if(is_object($quote)){
            $currentBilling = $this->hasDataSet($quote->getBillingAddress());
            $currentPrimaryBilling = $this->hasDataSet($quote->getCustomer()->getPrimaryBillingAddress());
            $difference  = array_diff($currentPrimaryBilling, $currentBilling);
            if(!empty($currentBilling) && !empty($difference)){
                foreach($this->defaultFields as $field){
                    $quote->getBillingAddress()->setData($field, '');
                    $quote->getShippingAddress()->setData($field, '');
                }
            }
            $observer->getEvent()->setQuote($quote);
            if(Mage::getStoreConfig('onepagecheckout/general/rewrite_checkout_links')) {
                $this->callDefaults($observer);
            }
            
            //if(Mage::getStoreConfig('onepagecheckout/general/include_js_valid', $quote->getStore())) {
           // $this->callDefaults($observer);

			//}
        }

        return $this;
    }

  
    public function compareDefaultsFromCart(Varien_Event_Observer $observer) {

        $quote = Mage::getSingleton('checkout/session')->getQuote();

        if(is_object($quote)){

            
            $currentBilling = $this->hasDataSet($quote->getBillingAddress());
            $currentShipping = $this->hasDataSet($quote->getShippingAddress());

            $sameAsBilling = $quote->getShippingAddress()->getSameAsBilling();
            $difference = array();

            if($sameAsBilling){
                if(Mage::getSingleton('customer/session')->isLoggedIn()){
                    $selectedAddress = $quote->getBillingAddress()->getCustomerAddressId();
                    if($selectedAddress){
                        $currentShippingOriginal = $this->hasDataSet($quote->getCustomer()->getAddressById($selectedAddress));
                        $difference = array_diff($currentShippingOriginal, $currentShipping);
                    } else {
                        $currentPrimaryBilling = $this->hasDataSet($quote->getCustomer()->getPrimaryBillingAddress());
                        $difference  = array_diff($currentPrimaryBilling, $currentBilling);
                    }
                } else {
                    $difference = array_diff($currentBilling, $currentShipping);
                }

                if(!empty($difference)){
                    $quote->getBillingAddress()->addData($difference)->implodeStreetAddress();
                    $quote->getShippingAddress()->addData($difference)->implodeStreetAddress()->setCollectShippingRates(true);
                }
            }

        }

        return $this;
    }

  
    public function setShippingIfDifferent(Varien_Event_Observer $observer){

        $quote = $observer->getEvent()->getQuote();

        if(!Mage::getStoreConfig('onepagecheckout/general/rewrite_checkout_links', $quote->getStore())) {
            return $this;
        }

        $newCode = Mage::getStoreConfig('onepagecheckout/general/default_shipping_method', $quote->getStore());

        if (empty($newCode)) {
            return $this;
        }

       
        $quote->getShippingAddress()->collectShippingRates();

        return $this;
    }

    
    public function setAddressDefaults(Varien_Event_Observer $observer) {

        $quote = $observer->getEvent()->getQuote();

        $checkPostcode = $this->getCheckPostcode();
        $currentBilling = $this->hasDataSet($quote->getBillingAddress(), $checkPostcode);
        $currentShipping = $this->hasDataSet($quote->getShippingAddress(), $checkPostcode);

        if (!is_object($quote) || (!empty($currentBilling) || !empty($currentShipping))) {
            return $this;
        }

        $newShipping = $this->getAddressDefaults($quote);
        $newBilling = $newShipping;

        if (empty($newShipping)) {
            return $this; 
        }

      
        if (Mage::getSingleton('customer/session')->isLoggedIn() && empty($currentBilling)) {

            
            $currentPrimaryBilling = $this->hasDataSet($quote->getCustomer()->getPrimaryBillingAddress(), $checkPostcode);
            $currentPrimaryShipping = $this->hasDataSet($quote->getCustomer()->getPrimaryShippingAddress(), $checkPostcode);

           
            if (empty($currentBilling) && ! empty($currentPrimaryBilling)) {
                $newBilling = $currentPrimaryBilling;
            }
            if (empty($currentShipping) && ! empty($currentPrimaryShipping)) {
                $newShipping = $currentPrimaryShipping;
            }
        }

       
        if ($quote->getShippingAddress()->getSameAsBilling()) {
            $newShipping = $newBilling;
        }

       
        if (empty($currentBilling) && ! empty($newBilling)) {
            $quote->getBillingAddress()->addData($newBilling);
        }

       
        if (empty($currentShipping) && ! empty($newShipping)) {
            $quote->getShippingAddress()->addData($newShipping);
            $quote->getShippingAddress()->setSameAsBilling(Mage::getStoreConfig('onepagecheckout/general/enable_different_shipping_hide', $quote->getStore()));
        }

        return $this;
    }

   
    public function getCheckPostcode (){
        return ((strstr(Mage::getStoreConfig('onepagecheckout/ajax_update/ajax_save_billing_fields'),'postcode')) ? true : false);
    }

   
    public function getAddressDefaults(Mage_Sales_Model_Quote $quote){

        $data = $this->getAddressGeoIP($quote);

        if(!empty($data)){
            return $data;
        }

        if($countryId = Mage::getStoreConfig('onepagecheckout/general/default_country',$quote->getStore())){
            $data['country_id'] = $countryId;
        }
        if($regionId = Mage::getStoreConfig('onepagecheckout/general/default_region_id',$quote->getStore())){
            $data['region_id'] = $regionId;
        }
        if($city = Mage::getStoreConfig('onepagecheckout/general/default_city',$quote->getStore())){
            $data['city'] = $city;
        }
        if($postcode = Mage::getStoreConfig('onepagecheckout/general/default_postcode',$quote->getStore())){
            $data['postcode'] = $postcode;
        }

        return $data;
    }


 
    public function setShippingDefaults(Varien_Event_Observer $observer) {

        $quote = $observer->getEvent()->getQuote();
        $newCode = Mage::getStoreConfig('onepagecheckout/general/default_shipping_method', $quote->getStore());
        $oldCode = $quote->getShippingAddress()->getShippingMethod();
        $codes = array();

        if (empty($newCode)) {
            return $this;
        }

        foreach ($this->getEstimateRates($quote) as $rates) {
            foreach ($rates as $rate) {
                $codes[] = $rate->getCode();
            }
        }

        if (empty($codes)) {
            return $this;
        }

        $codeCount = (int)count($codes);

   
        if ($codeCount === 1) {
            if(Mage::getStoreConfig('onepagecheckout/general/default_shipping_if_one', $quote->getStore())){
                $newCode = current($codes);
            }
        }

        if (! empty($codes) && (empty($oldCode) || ! in_array($oldCode, $codes))) {
            if (in_array($newCode, $codes)) {
                $quote->getShippingAddress()->setShippingMethod($newCode);
            }
        }

        return $this;
    }

    public function getEstimateRates(Mage_Sales_Model_Quote $quote) {
        if (empty($this->_rates)) {
            $groups = $quote->getShippingAddress()->getGroupedAllShippingRates();
            $this->_rates = $groups;
        }
        return $this->_rates;
    }

    public function setPaymentDefaults(Varien_Event_Observer $observer) {
        $quote = $observer->getEvent()->getQuote();
        $newCode = Mage::getStoreConfig('onepagecheckout/general/default_payment_method', $quote->getStore());

        if (empty($newCode)) {
            return;
        }

        $codes = $this->getPaymentMethods($quote);

        if (empty($codes) || !is_object($quote)|| !$quote->getGrandTotal()) {
            return;
        }

        $codeCount = (int)count($codes);

        
        if ($codeCount === 1 && current($codes) !='free') {
            $newCode = current($codes);
        }

        $oldCode = $quote->getPayment()->getMethod();

        if (!empty($codes) && (empty($oldCode) || !in_array($oldCode, $codes))) {
            if (in_array($newCode, $codes)) {

                if(Mage::getStoreConfig('payment/'.$newCode.'/active', $quote->getStore())){
                    if ($quote->isVirtual()) {
                        $quote->getBillingAddress()->setPaymentMethod($newCode);
                    } else {
                        $quote->getShippingAddress()->setPaymentMethod($newCode);
                    }
                    try {
                        $quote->getPayment()->setMethod($newCode)->getMethodInstance();
                    } catch ( Exception $e ) {
                        Mage::logException($e);
                    }
                }
            }
        }
    }

  
    public function getPaymentMethods(Mage_Sales_Model_Quote $quote) {

        $methods = $this->_methods;
        if (empty($methods)) {
            $store = $quote ? $quote->getStoreId() : null;
            $methodInstances = Mage::helper('payment')->getStoreMethods($store, $quote);
            $total = $quote->getGrandTotal();
            foreach ($methodInstances as $key => $method) {
                if ($this->_canUseMethod($method, $quote)
                        && ($total != 0
                                || $method->getCode() == 'free'
                                || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles()))) {
                    $methods[] = $method->getCode();
                } else {
                    unset($methods[$key]);
                }
            }

            $this->_methods = $methods;
        }
        return $this->_methods;
    }

 
    protected function _canUseMethod($method, $quote)
    {
        if (!$method->canUseForCountry($quote->getBillingAddress()->getCountry())) {
            return false;
        }

        if (method_exists($method,'canUseForCurrency') && !$method->canUseForCurrency(Mage::app()->getStore()->getBaseCurrencyCode())) {
            return false;
        }

      
        $total = $quote->getBaseGrandTotal();
        $minTotal = $method->getConfigData('min_order_total');
        $maxTotal = $method->getConfigData('max_order_total');

        if((!empty($minTotal) && ($total < $minTotal)) || (!empty($maxTotal) && ($total > $maxTotal))) {
            return false;
        }
        return true;
    }

    public function hasDataSet($address, $checkPostcode = false){

        $data = array();

        if(is_object($address)){
            foreach($address->getData() as $key => $value){
                if(in_array($key, $this->defaultFields) && !empty($value)){
                    $data[$key] = $value;
                }
            }
        }

        if($checkPostcode && empty($data['postcode'])){
            $data = array();
        }
        return $data;
    }

}
