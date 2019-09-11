<?php
class Rokanthemes_Upsellslider_Block_Catalog_Product_List_Upsell extends Mage_Catalog_Block_Product_List_Upsell
{

    protected $_config = array();

    protected function _construct()
    {
        if(!$this->_config) $this->_config = Mage::getStoreConfig('upsellslider/general'); 
        parent::_construct();
    }
    public function getTemplate()
    {
        return 'rokanthemes/upsellslider/upsellslider.phtml';
    }
    public function getConfig($cfg = null)
    {
        if (isset($this->_config[$cfg]) ) return $this->_config[$cfg];
        return ; // return $this->_config;
    }

    public function getItemLimit($type = '')
    {
        return Mage::getStoreConfig('upsellslider/general/qty');
    }
    
    public function setBxslider()
    {
        $options = array(
            'auto',
            'speed',
            'controls',
            'pager',
            'maxSlides',
            'slideWidth',
        );
        $script = '';
        foreach ($options as $opt) {
            $cfg  =  $this->getConfig($opt);
            $script    .= "$opt: $cfg, ";
        }

        $options2 = array(
            'mode'=>'vertical',
        );
        foreach ($options2 as $key => $value) {
            $cfg  =  $this->getConfig($value);
            if($cfg) $script    .= "$key: '$value', ";
        }

        return $script;

    }

}
