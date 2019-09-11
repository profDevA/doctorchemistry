<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */


class Ocodewire_OnePageCheckout_IndexController extends Mage_Core_Controller_Front_Action {
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        return $this;
    }

    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    public function successAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function indexAction() {

        $routeName = $this->getRequest()->getRouteName();
        
		if (Mage::helper('onepagecheckout')->isRewriteCheckoutLinksEnabled() == 0 || $routeName != 'onepagecheckout'){
            $this->_redirect('checkout/onepage', array('_secure'=>true));
        }
        
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        $this->loadLayout();

     // if (Mage::helper('onepagecheckout')->includeJsValid() == 1 ){
            
              //echo'onepagecheckout/onepagecheckout.css';
             // $this->getLayout->getBlock('head')->addItem('skin_css', 'onepagecheckout/onepagecheckout.css');
             //$head = Mage::app()->getLayout()->getBlock('head');
			 // $head->addItem('skin_css', 'onepagecheckout/onepagecheckout.css1221');
			
			 
       // }
        

        if(is_object(Mage::getConfig()->getNode('global/models/googleoptimizer')) && Mage::getStoreConfigFlag('google/optimizer/active')){
           $googleOptimizer = $this->getLayout()->createBlock('googleoptimizer/code_conversion', 'googleoptimizer.conversion.script', array('after'=>'-'))
            ->setScriptType('conversion_script')
            ->setPageType('checkout_onepage_success');
            $this->getLayout()->getBlock('before_body_end')
            ->append($googleOptimizer);
        }

        $this->renderLayout();
    }

    
    protected function _preDispatchValidateCustomer($redirect = true, $addErrors = true)
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if ($customer && $customer->getId()) {
            $validationResult = $customer->validate();
            if ((true !== $validationResult) && is_array($validationResult)) {
                if ($addErrors) {
                    foreach ($validationResult as $error) {
                        Mage::getSingleton('customer/session')->addError($error);
                    }
                }
                if ($redirect) {
                    $this->_redirect('customer/account/edit');
                    $this->setFlag('', self::FLAG_NO_DISPATCH, true);
                }
                return false;
            }
        }
        return true;
    }
}
