<?php
class Rokanthemes_Zoom_Block_Zoom extends Mage_Catalog_Block_Product_View_Media
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getZoom()     
     { 
        if (!$this->hasData('zoom')) {
            $this->setData('zoom', Mage::registry('zoom'));
        }
        return $this->getData('zoom');
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('zoom');
		if (isset($config['zoom_config']) ) {
			$value = $config['zoom_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}