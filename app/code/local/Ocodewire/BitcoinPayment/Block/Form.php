<?php


class Ocodewire_BitcoinPayment_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('bitcoinpayment/form.phtml');
    }


    public function getCustomText($addNl2Br = true)
    {
        return $this->getMethod()->getCustomText($addNl2Br);
    }
}
