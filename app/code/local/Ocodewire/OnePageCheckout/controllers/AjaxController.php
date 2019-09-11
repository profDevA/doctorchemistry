<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_AjaxController extends Mage_Core_Controller_Front_Action
{


    public function indexAction()
    {
        $resource = Mage::getResourceModel('sales/order_collection');

        if(method_exists($resource, 'getEntity'))   {
            echo 'Is using EAV';
        }
        else {
            echo 'Not using EAV';
        }

        die();

        var_dump($resource->getEntity());
        var_dump(get_class_methods($resource->getEntity()));
        var_dump($resource);

        die();

        var_dump(get_class_methods($resource));




        echo get_class($collection);
        echo '<br>';
        echo get_class($sales);

        var_dump(get_class_methods($collection));

        var_dump(get_class_methods($sales));
        var_dump($sales);

        die('<br><br>ajaxcontroller!');

        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _isEmailRegistered($email)
    {
        $model = Mage::getModel('customer/customer');
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);

        if($model->getId() == NULL)    {
            return false;
        }

        return true;
    }

    public function cupon_discountAction()
    {
        $quote = $this->_getOnepage()->getQuote();
        $couponCode = (string)$this->getRequest()->getParam('code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }

        $response = array(
            'success' => false,
            'error'=> false,
            'message' => false,
        );



        try {

            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
            ->collectTotals()
            ->save();

            if ($couponCode) {
                if ($couponCode == $quote->getCouponCode()) {
                    $response['success'] = true;
                    $response['message'] = $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->escapeHtml($couponCode));
                }
                else {
                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode));
                }
            } else {
                $response['success'] = true;
                $response['message'] = $this->__('Coupon code was canceled successfully.');
            }


        }
        catch (Mage_Core_Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        catch (Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Can not apply coupon code.');
        }




        $html = $this->getLayout()
        ->createBlock('checkout/onepage_shipping_method_available')
        ->setTemplate('onepagecheckout/shipping_method.phtml')
        ->toHtml();

        $response['shipping_method'] = $html;


        $html = $this->getLayout()
        ->createBlock('checkout/onepage_payment_methods','choose-payment-method')
        ->setTemplate('onepagecheckout/payment_method.phtml');

    
        $response['payment_method'] = $html->toHtml();

         
        $html = $this->getLayout()
        ->createBlock('onepagecheckout/summary')
        ->setTemplate('onepagecheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;

        $this->getResponse()->setBody(Zend_Json::encode($response));
    }

 
    public function validate_emailAction()
    {
        $validator = new Zend_Validate_EmailAddress();
        $email = $this->getRequest()->getPost('email', false);

        $data = array('result'=>'invalid');


        if($email && $email != '')  {
            if(!$validator->isValid($email))    {

            }
            else    {

                
                if($this->_isEmailRegistered($email))   {
                    $data['result'] = 'exists';
                }
                else    {
                    $data['result'] = 'clean';
                }
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($data));
    }

    public function forgot_passwordAction()
    {
        $email = $this->getRequest()->getPost('email', false);

        if (!Zend_Validate::is($email, 'EmailAddress')) {
            $result = array('success'=>false);
        }
        else    {

            $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                    $result = array('success'=>true);
                }
                catch (Exception $e){
                    $result = array('success'=>false, 'error'=>$e->getMessage());
                }
            }
            else {
                $result = array('success'=>false, 'error'=>'notfound');
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function loginAction()
    {

        $username = $this->getRequest()->getPost('onepagecheckout_username', false);
        $password = $this->getRequest()->getPost('onepagecheckout_password',  false);
        $session = Mage::getSingleton('customer/session');

        $result = array('success' => false);

        if ($username && $password) {
            try {
                $session->login($username, $password);			
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            if (! isset($result['error'])) {
                $result['success'] = true;
            }
        } else {
            $result['error'] = $this->__(
            'Please enter a username and password.');
        }


        $this->getResponse()->setBody(Zend_Json::encode($result));

    }

    public function billing_detailsAction()
    {
        $helper = Mage::helper('onepagecheckout/checkout');

        $billing_data = $this->getRequest()->getPost('billing', array());
         $shipping_data = $this->getRequest()->getPost('shipping', array());
         $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
         $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);

        $billing_data = $helper->load_exclude_data($billing_data);
        $shipping_data = $helper->load_exclude_data($shipping_data);

        if(Mage::helper('customer')->isLoggedIn() && $helper->differentShippingAvailable()){
            if(!empty($customerAddressId)){
                $billingAddress = Mage::getModel('customer/address')->load($customerAddressId);
                if(is_object($billingAddress) && $billingAddress->getCustomerId() ==  Mage::helper('customer')->getCustomer()->getId()){
                    $billing_data = array_merge($billing_data, $billingAddress->getData());
                }
            }
            if(!empty($shippingAddressId)){
                $shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
                if(is_object($shippingAddress) && $shippingAddress->getCustomerId() ==  Mage::helper('customer')->getCustomer()->getId()){
                    $shipping_data = array_merge($shipping_data, $shippingAddress->getData());
                }
            }
        }

        if(!empty($billing_data['use_for_shipping'])) {
           $shipping_data = $billing_data;
        }

       
        $taxid = '';
        if(!empty($billing_data['taxvat'])){
            $taxid = $billing_data['taxvat'];
        } else if(!empty($billing_data['vat_id'])){
            $taxid = $billing_data['vat_id'];
        }
        if (!empty($taxid)) {
            $this->_getOnepage()->getQuote()->setCustomerTaxvat($taxid);
            $this->_getOnepage()->getQuote()->setTaxvat($taxid);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxvat($taxid);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxId($taxid);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setVatId($taxid);
        } else {
            $this->_getOnepage()->getQuote()->setCustomerTaxvat('');
            $this->_getOnepage()->getQuote()->setTaxvat('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxvat('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxId('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setVatId('');
        }

        $this->_getOnepage()->getQuote()->getBillingAddress()->addData($billing_data)->implodeStreetAddress()->setCollectShippingRates(true);
        if(!$this->_getOnepage()->getQuote()->isVirtual() && !Mage::helper('customer')->isLoggedIn()){
            $this->_getOnepage()->getQuote()->getShippingAddress()->addData($shipping_data)->implodeStreetAddress()->setCollectShippingRates(true);
        }

        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        $selectedMethod = $this->_getOnepage()->getQuote()->getPayment()->getMethod();

        $store = $this->_getOnepage()->getQuote() ? $this->_getOnepage()->getQuote()->getStoreId() : null;
        $methods = $helper->getActiveStoreMethods($store, $this->_getOnepage()->getQuote());

        if($paymentMethod && !empty($methods) && !in_array($paymentMethod, $methods)){
            $paymentMethod = false;
        }

        if(!$paymentMethod && $selectedMethod && in_array($selectedMethod, $methods)){
             $paymentMethod = $selectedMethod;
        }

        if($this->_getOnepage()->getQuote()->isVirtual()) {
            $this->_getOnepage()->getQuote()->getBillingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        } else {
            $this->_getOnepage()->getQuote()->getShippingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        }

        try {
            if($paymentMethod){
                $this->_getOnepage()->getQuote()->getPayment()->getMethodInstance();
            }
        } catch (Exception $e) {
        }

        $result = $this->_getOnepage()->saveBilling($billing_data, $customerAddressId);

        if(Mage::helper('customer')->isLoggedIn()){
            $this->_getOnepage()->getQuote()->getBillingAddress()->setSaveInAddressBook(empty($billing_data['save_in_address_book']) ? 0 : 1);
            $this->_getOnepage()->getQuote()->getShippingAddress()->setSaveInAddressBook(empty($shipping_data['save_in_address_book']) ? 0 : 1);
        }

        if($helper->differentShippingAvailable()) {
            if(empty($billing_data['use_for_shipping'])) {
                $shipping_result = $helper->saveShipping($shipping_data, $shippingAddressId);
            }else {
                $shipping_result = $helper->saveShipping($billing_data, $customerAddressId);
            }
        }

        $shipping_method = $this->getRequest()->getPost('shipping_method', false);

        if(!empty($shipping_method)) {
           $helper->saveShippingMethod($shipping_method);
        }

        if(!Mage::helper('customer')->isLoggedIn()){
            $this->_getOnepage()->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        }

        $this->loadLayout(false);

       

       

        $this->renderLayout();

    }

    public function updated_paymentAction()
    {
        $payment_method = $this->getRequest()->getPost('payment_method');
        $helper = Mage::helper('onepagecheckout/checkout');
        if($payment_method != '')   {
            try {
                $payment = $this->getRequest()->getPost('payment', array());
                $payment['method'] = $payment_method;
                $this->_getOnepage()->getQuote()->getPayment()->setMethod($payment['method'])->getMethodInstance();

                $helper->savePayment($payment);
            }
            catch(Exception $e) {
                
            }
        }

        $this->loadLayout(false);

       

        $this->renderLayout();
    }

    public function set_methods_separateAction()
    {
        $helper = Mage::helper('onepagecheckout/checkout');

        $shipping_method = $this->getRequest()->getPost('shipping_method');
        $old_shipping_method = $this->_getOnepage()->getQuote()->getShippingAddress()->getShippingMethod();

        if($shipping_method != '' && $shipping_method != $old_shipping_method)  {
           
            $helper->saveShippingMethod($shipping_method);
        }


        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        $selectedMethod = $this->_getOnepage()->getQuote()->getPayment()->getMethod();

        $store = $this->_getOnepage()->getQuote() ? $this->_getOnepage()->getQuote()->getStoreId() : null;
        $methods = $helper->getActiveStoreMethods($store, $this->_getOnepage()->getQuote());

        if($paymentMethod && !empty($methods) && !in_array($paymentMethod, $methods)){
            $paymentMethod = false;
        }

        if(!$paymentMethod && $selectedMethod && in_array($selectedMethod, $methods)){
             $paymentMethod = $selectedMethod;
        }

        try {
            $payment = $this->getRequest()->getPost('payment', array());
            
            if(!empty($paymentMethod)){
                $payment['method'] = $paymentMethod;
            }

            $helper->savePayment($payment);
        }
        catch(Exception $e) {


        }
        $this->_getOnepage()->getQuote()->collectTotals()->save();
        $this->loadLayout(false);

      

        $this->renderLayout();
    }

    public function set_methodsAction()
    {
        $helper = Mage::helper('onepagecheckout/checkout');
        $shipping_method = $this->getRequest()->getPost('shipping_method');

        if($shipping_method != '')  {

            $helper->saveShippingMethod($shipping_method);
        }

        $payment_method = $this->getRequest()->getPost('payment_method');

        if($payment_method != '')   {
            try {
                $payment = $this->getRequest()->getPost('payment', array());
                $payment['method'] = $payment_method;

                $helper->savePayment($payment);
            }
            catch(Exception $e) {
               
            }
        }



        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function set_payment_methodAction()
    {
        $payment_method = $this->getRequest()->getPost('payment_method');
        $payment = array('method' => $payment_method);
        $result = $this->_getOnepage()->savePayment($payment);

        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function set_shipping_methodAction()
    {
        $shipping_method = $this->getRequest()->getPost('shipping_method');
        $result = $this->_getOnepage()->saveShippingMethod($shipping_method);

        $this->loadLayout(false);
        $this->renderLayout();
    }

    protected function _getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
    

public function customer_registrationAction() {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (! $customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            $lastOrderId = $this->_getOnepage()->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);
            $billing = $order->getBillingAddress();
            $shipping = $order->getShippingAddress();

            $customer->setData('firstname', $billing->getFirstname());
            $customer->setData('lastname', $billing->getLastname());
            $customer->setData('email', $order->getCustomerEmail());

            foreach (Mage::getConfig()->getFieldset('customer_account') as $code => $node) {
                if ($node->is('create') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

           
            $customer->getGroupId();

            $baddress = Mage::getModel('customer/address')->setData($billing->getData())->setIsDefaultBilling(1)->setId(null);
            $customer->addAddress($baddress);
            $saddress = Mage::getModel('customer/address')->setData($shipping->getData())->setIsDefaultShipping(1)->setId(null);
            $customer->addAddress($saddress);

            $result = array('success' => false, 'message' => false, 'error' => false );

            try {
                $validationCustomer = $customer->validate();
                if (is_array($validationCustomer)) {
                    $errors = array_merge($validationCustomer, $errors);
                }
                $validationResult = count($errors) == 0;
                if (true === $validationResult) {

                    $customer->save();

                    $result['success'] = true;

                    if ($customer->isConfirmationRequired()) {

                        $customer->sendNewAccountEmail('confirmation', $this->_getSession()->getBeforeAuthUrl());
                        $this->_getSession()->addSuccess($this->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));

                        $result['message'] = 'email_confirmation';

                    } else {
                        $this->_getSession()->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);

                        $result['message'] = 'customer_logged_in';
                    }


                    $order->setCustomerId($customer->getId());
                    $order->setCustomerIsGuest(false);
                    $order->setCustomerGroupId($customer->getGroupId());
                    $order->save();

                } else {
                    $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                        }

                        $result['error'] = 'validation_failed';
                        $result['errors'] = $errors;
                    } else {
                        $result['error'] = 'invalid_customer_data';
                    }
                }
            } catch ( Mage_Core_Exception $e ) {

                $result['error'] = $e->getMessage();

            } catch ( Exception $e ) {

                $result['error'] = $e->getMessage();

            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess($this->__('Thank you for registering with %s', Mage::app()->getStore()->getName()));

        $customer->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

        $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }
}
