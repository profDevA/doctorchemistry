<?php

class Rokanthemes_Layerednavigation_Block_Catalogsearch_Layer_Filter_Decimal extends Mage_CatalogSearch_Block_Layer_Filter_Decimal {
      public function __construct()
    {
        parent::__construct();
        $enableModule = Mage::helper('layerednavigation/data')->getStoreConfigField('enabled');
        if($enableModule){
            $this->setTemplate('rokanthemes/layerednavigation/attribute.phtml');
        }
    }
}
