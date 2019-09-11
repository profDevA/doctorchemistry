<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function checkValid($observer)
    {
        $layout = Mage::app()->getLayout();
        $content = $layout->getBlock('content');
        $block = $layout->createBlock('onepagecheckout/valid');
        $content->insert($block);
    }

	//public function includeJsValid()
	//{
		//echo	Mage::getStoreConfig('onepagecheckout/general/include_js_valid');
		//return Mage::getStoreConfig('onepagecheckout/general/include_js_valid');
		
	//}

    public function isRewriteCheckoutLinksEnabled()
    {
        return Mage::getStoreConfig('onepagecheckout/general/rewrite_checkout_links');
    }

   
    public function isEnterPrise(){
       //die('i am here');
    }

   
    public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array())
    {
        $json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);

        $inline = Mage::getSingleton('core/translate_inline');
        if ($inline->isAllowed()) {
            $inline->setIsJson(true);
            $inline->processResponseBody($json);
            $inline->setIsJson(false);
        }

        return $json;
    }

    
    public function clearDash($value = null){
        if($value == '-'){
            return '';
        }
        if(method_exists(Mage::helper('core'), 'escapeHtml')){
            return Mage::helper('core')->escapeHtml($value);
        } else {

            return Mage::helper('core')->htmlEscape($value);
        }
    }
}
