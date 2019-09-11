<?php

class Rokanthemes_Blog_Block_Manage_Blog extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'manage_blog';
        $this->_blockGroup = 'blog';
        $this->_headerText = Mage::helper('blog')->__('Blog Post Manager');
        parent::__construct();
        $this->setTemplate('rokanthemes_blog/posts.phtml');
    }

    protected function _prepareLayout()
    {
        $addButtonBlock = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                     'label'   => Mage::helper('blog')->__('Add Post'),
                     'onclick' => "setLocation('" . $this->getUrl('*/*/new') . "')",
                     'class'   => 'add',
                )
            )
        ;
        $this->setChild('add_new_button', $addButtonBlock);

        /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $storeSwitcherBlock = $this->getLayout()->createBlock('adminhtml/store_switcher')
                ->setUseConfirm(false)
                ->setSwitchUrl($this->getUrl('*/*/*', array('store' => null)))
            ;
            $this->setChild('store_switcher', $storeSwitcherBlock);
        }
        $this->setChild('grid', $this->getLayout()->createBlock('blog/manage_blog_grid', 'blog.grid'));
        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getStoreSwitcherHtml()
    {
        return $this->getChildHtml('store_switcher');
    }
}