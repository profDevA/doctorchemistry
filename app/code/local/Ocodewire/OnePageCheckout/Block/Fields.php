<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

class Ocodewire_OnePageCheckout_Block_Fields extends Ocodewire_OnePageCheckout_Block_Checkout
{
    public function _construct(){
        $this->setSubTemplate(true);
        parent::_construct();
    }
}
