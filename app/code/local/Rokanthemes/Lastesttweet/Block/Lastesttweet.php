<?php 
class Rokanthemes_Lastesttweet_Block_Lastesttweet extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {	
		return parent::_prepareLayout();
    }
    
     public function getLastesttweet()     
     { 	if (!$this->hasData('lastesttweet')) {
            $this->setData('lastesttweet', Mage::registry('lastesttweet'));
        }
        return $this->getData('lastesttweet');
        
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('lastesttweet');
		if (isset($config['lastesttweet_config']) ) {
			$value = $config['lastesttweet_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}