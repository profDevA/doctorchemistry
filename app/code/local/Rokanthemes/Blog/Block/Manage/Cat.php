<?php

class Rokanthemes_Blog_Block_Manage_Cat extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'manage_cat';
        $this->_blockGroup = 'blog';
        $this->_headerText = Mage::helper('blog')->__('Category Manager');
        parent::__construct();
    }
}