<?php
class Rokanthemes_Layerednavigation_Block_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    public function __construct()
    {
        
        parent::__construct();
        
        if(Mage::getStoreConfig('layerednavigation/layerfiler_config/enabled')){
            $this->setTemplate('rokanthemes/layerednavigation/attribute.phtml');
        }
    }
}