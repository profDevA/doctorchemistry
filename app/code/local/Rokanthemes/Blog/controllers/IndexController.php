<?php

class Rokanthemes_Blog_IndexController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::helper('blog')->getEnabled()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
        Mage::helper('blog')->ifStoreChangedRedirect();
    }

    public function indexAction()
    {
        $this->_forward('list');
    }

    public function listAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate(Mage::helper('blog')->getLayout());
        $this->renderLayout();
    }
}