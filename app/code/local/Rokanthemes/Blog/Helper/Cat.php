<?php

class Rokanthemes_Blog_Helper_Cat extends Mage_Core_Helper_Abstract
{
    /**
     * Renders CMS page
     * Call from controller action
     *
     * @param Mage_Core_Controller_Front_Action $action
     * @param integer                           $identifier
     *
     * @return bool
     */
    public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier = null)
    {
        if (!$catId = Mage::getSingleton('blog/cat')->load($identifier)->getCatId()) {
            return false;
        }

        $pageTitle = Mage::getSingleton('blog/cat')->load($identifier)->getTitle();
        $blogTitle = Mage::getStoreConfig('blog/blog/title') . " - " . $pageTitle;

        $action->loadLayout();
        if ($storage = Mage::getSingleton('customer/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
        $action->getLayout()->getBlock('head')->setTitle($blogTitle);

        $action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));
        $action->renderLayout();

        return true;
    }
}