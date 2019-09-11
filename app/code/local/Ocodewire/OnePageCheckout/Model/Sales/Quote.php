<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class  Ocodewire_OnePageCheckout_Model_Sales_Quote extends Mage_Sales_Model_Quote
{
    public function collectTotals()
    {

       if (!$this->getTotalsCollectedFlag()) {

            $items = $this->getAllItems();

            foreach($items as $item){
                $item->setData('calculation_price', null);
                $item->setData('original_price', null);
            }

        }

        parent::collectTotals();
        return $this;

    }

}
