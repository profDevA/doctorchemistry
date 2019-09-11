<?php

class Rokanthemes_Blog_Helper_Comment extends Mage_Core_Helper_Abstract
{
    public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier = null, $data = array())
    {
        $page = Mage::getSingleton('blog/blog');
        if (!is_null($identifier) && $identifier !== $page->getId()) {
            $page->setStoreId(Mage::app()->getStore()->getId());
            if (!$page->load($identifier)) {
                return false;
            }
        }

        if (!$page->getId()) {
            return false;
        }
        if ($page->getStatus() == 2) {
            return false;
        }
        $pageTitle = Mage::getSingleton('blog/blog')->load($identifier)->getTitle();
        $blogTitle = Mage::getStoreConfig('blog/blog/title') . " - ";

        $action->loadLayout();
        if ($storage = Mage::getSingleton('customer/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
        $action->getLayout()->getBlock('head')->setTitle($blogTitle . $pageTitle);
        $action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));
        $action->getLayout()->getBlock('post')->setCommentDetails($data['user'], $data['email'], $data['comment']);
        $action->renderLayout();
        return true;
    }
}