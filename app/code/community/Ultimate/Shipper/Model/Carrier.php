<?php
/**
* Ultimate_Shipper extension
*
* NOTICE OF LICENSE
*
* This source file is subject to the MIT License
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/mit-license.php
*
* @category       Shipping & Fulfillment
* @package        Ultimate_Shipper
* @copyright      Copyright (c) 2015
* @license        http://opensource.org/licenses/mit-license.php MIT License
*/
/**
* Method model
*
* @category    Ultimate
* @package     Ultimate_Shipper
* @author      RSMD Partners
*/
class Ultimate_Shipper_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Code of the carrier
     *
     * @var string
     */
    const CODE = 'ultimate_shipper';

    /**
     * Code of the carrier
     *
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * Container types that could be customized
     *
     * @var array
     */
    const HANDLING_TYPE_PERCENT = 'P';
    const HANDLING_TYPE_FIXED = 'F';

    const HANDLING_ACTION_PERPACKAGE = 'P';
    const HANDLING_ACTION_PERORDER = 'O';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        
        /**
         * Get If method is enable or not
         *  
         * @return boolean
         * @author RSMD Partners
         */
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /**
         * Global model declaration for shipping model
         *  
         * @var $result Mage_Shipping_Model_Rate_Result 
         * @author RSMD Partners
         */
        $result = Mage::getModel('shipping/rate_result');
            
        /**
         * If the total cart value with discount exceeds
         * the Minimum Order Amount for Free Shipping
         * append the free shipping rate to the result.
         *
         * @return $result
         * @author RSMD Partners
         */
        

        /**
         * Restrict Other Methods If Free Shipping 
         * Criteria is Met is enable then append free
         * shipping and return function else proceed
         * with the rest of the methods.
         * 
         * @return boolean
         * @author RSMD Partners
         */
        if ($this->getConfigData('freemethods_restrict_others') == 1) 
        {                        
            if($this->getConfigData('freemethods_active')==1){

                if ($request->getPackageValueWithDiscount() 
                  >= $this->getConfigData('freemethods_order_total')) 
                {            
                    $freeShippingRate = $this->_getFreeShippingRate();
                    $result->append($freeShippingRate);
                }
            }
            
        } else {

            /* Free Shipping */
            if($this->getConfigData('freemethods_active')==1){

                if ($request->getPackageValueWithDiscount() 
                  >= $this->getConfigData('freemethods_order_total')) 
                {            
                    $freeShippingRate = $this->_getFreeShippingRate();
                    $result->append($freeShippingRate);
                }
            } 

            /* Other Allowed Methods */
            $methodsArray = explode(',', $this->getConfigData('allowedmethods'));
        
            for($i=0;$i<count($methodsArray);$i++){

                $custommethod = Mage::getModel('ultimate_shipper/method')
                                    ->getCollection()
                                    ->addFieldToFilter('entity_id', array("eq" => $methodsArray[$i]))
                                    ->getData();

                if(($custommethod[0]['status']==1) && (!$this->_error)) {
                                
                /**
                 * Per method type price calculation, 
                 * Handling type based handling fee 
                 * Calculation.
                 */
                /**
                 * Shipping price calculation.
                 */
                $freeBoxes = 0;
                if ($request->getAllItems()) {
                    foreach ($request->getAllItems() as $item) {

                        if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                            continue;
                        }

                        if ($item->getHasChildren() && $item->isShipSeparately()) {
                            foreach ($item->getChildren() as $child) {
                                if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                                    $freeBoxes += $item->getQty() * $child->getQty();
                                }
                            }
                        } elseif ($item->getFreeShipping()) {
                            $freeBoxes += $item->getQty();
                        }
                    }
                }
                $this->setFreeBoxes($freeBoxes);

                if (($custommethod[0]['method_type'] == 'O') || ($custommethod[0]['method_type'] == NULL)) { // per order
                    $shippingPrice = $custommethod[0]['method_rate'];
                } elseif ($custommethod[0]['method_type'] == 'I') { // per item
                    $shippingPrice = ($request->getPackageQty() * $custommethod[0]['method_rate']) 
                                        - ($this->getFreeBoxes() * $custommethod[0]['method_rate']);
                } else {
                    $shippingPrice = false;
                }

                if($custommethod[0]['handling_type']!=NULL){

                    $handlingFee = $custommethod[0]['handling_fee'];
                    $handlingType = $custommethod[0]['handling_type'];
                    $handlingApplied = $custommethod[0]['handling_action'];
                    $shippingPrice = $this->getFinalCostWithHandlingFee(
                                        $shippingPrice, $handlingType, $handlingApplied, $handlingFee
                                     );
                }

                /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
                $rate = Mage::getModel('shipping/rate_result_method');
                $rate->setCarrier($this->_code);
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($custommethod[0]['method_identifier']);
                $rate->setMethodTitle($custommethod[0]['method_name']);
                $rate->setPrice($shippingPrice);
                $rate->setCost($shippingPrice);
                $result->append($rate);

            } elseif($custommethod[0]['show_method'] == 1) {
                
                $error = Mage::getModel('shipping/rate_result_error');
                $error->setCarrier($this->_code);
                $error->setErrorMessage($custommethod[0]['error_text']);
                $result->append($error);

            } else {

                /**
                 * Do Nothing Block
                 * 
                 * Do not remove this else block.
                 */
            }
            
            }

        }

        return $result;
    }

    /**
     * Get the handling fee for the shipping + cost
     *
     * @param float $cost
     * @return float final price for shipping method
     */
    public function getFinalCostWithHandlingFee($cost, $handlingType, $handlingAction, $handlingFee)
    {
        if (!$handlingType) {
            $handlingType = self::HANDLING_TYPE_FIXED;
        }
        
        if (!$handlingAction) {
            $handlingAction = self::HANDLING_ACTION_PERORDER;
        }

        return $handlingAction == self::HANDLING_ACTION_PERPACKAGE
            ? $this->_getPerPackageCost($cost, $handlingType, $handlingFee)
            : $this->_getPerOrderCost($cost, $handlingType, $handlingFee);
    }

    /**
     * Get final price for shipping method with handling fee per package
     *
     * @param  float $cost
     * @param  string $handlingType
     * @param  float $handlingFee
     * @return float
     */
    protected function _getPerPackageCost($cost, $handlingType, $handlingFee)
    {
        if ($handlingType == self::HANDLING_TYPE_PERCENT) {
            return ($cost + ($cost * $handlingFee/100)) * $this->_numBoxes;
        }

        return ($cost + $handlingFee) * $this->_numBoxes;
    }

    /**
     * Get final price for shipping method with handling fee per order
     *
     * @param  float $cost
     * @param  string $handlingType
     * @param  float $handlingFee
     * @return float
     */
    protected function _getPerOrderCost($cost, $handlingType, $handlingFee)
    {
        if ($handlingType == self::HANDLING_TYPE_PERCENT) {
            return ($cost * $this->_numBoxes) + ($cost * $this->_numBoxes * $handlingFee / 100);
        }

        return ($cost * $this->_numBoxes) + $handlingFee;
    }
      
    /** 
     * @access protected
     * @var $rate Mage_Shipping_Model_Rate_Result_Method
     * @return $rate or boolean
     *
     * @author RSMD Partners
     */
    protected function _getFreeShippingRate()
    {
        $custommethod = Mage::getModel('ultimate_shipper/method')
                            ->getCollection()
                            ->addFieldToFilter('entity_id', array("eq" => $this->getConfigData('freemethods')))
                            ->addFieldToFilter('status', array("eq" => 1))
                            ->getData();
        if(count($custommethod)==1){
            $rate = Mage::getModel('shipping/rate_result_method');
            $rate->setCarrier($this->_code);
            $rate->setCarrierTitle($this->getConfigData('title'));
            $rate->setMethod($custommethod[0]['method_identifier']);
            $rate->setMethodTitle($custommethod[0]['method_name']);
            $rate->setPrice($custommethod[0]['method_rate']);
            $rate->setCost(0);
            return $rate;
        } else {
            return false;
        }        
    }
    
    public function getAllowedMethods() {
        return array(
            'free_shipping' => $this->getConfigData('title'),
        );
    }
}