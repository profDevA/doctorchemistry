<?php

class SaveTheMage_CustomInvoiceAndOrderNumber_Block_FormCustomInvoiceAndOrderNumber extends Mage_Core_Block_Template
{
	
    public function __construct()
    {   	
        parent::__construct();
        $this->setTemplate('SaveTheMage/FormCustomInvoiceAndOrderNumber.phtml');   
    }	 
    
}