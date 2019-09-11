<?php

class Ocodewire_BitcoinPayment_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('bitcoinpayment/info.phtml');
    }

    public function toPdf()
    {
        $this->setTemplate('bitcoinpayment/pdf/info.phtml');
        return $this->toHtml();
    }

    public function getAccounts() {
        return $this->getMethod()->getAccounts();
    }

   /* public function getShowBankAccountsInPdf() {
        return $this->getMethod()->getConfigData('show_bank_accounts_in_pdf');
    }

    public function getShowCustomTextInPdf() {
        return $this->getMethod()->getConfigData('show_customtext_in_pdf');
    }*/
}
