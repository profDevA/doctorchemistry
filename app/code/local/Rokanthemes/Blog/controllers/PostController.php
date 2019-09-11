<?php

require_once 'recaptcha/recaptchalib-rokanthemes.php';

class Rokanthemes_Blog_PostController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::helper('blog')->getEnabled()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
    }

    protected function _validateData($data)
    {
        $errors = array();
        $helper = Mage::helper('blog');

        if (!Zend_Validate::is($data->getUser(), 'NotEmpty')) {
            $errors[] = $helper->__('Name can\'t be empty');
        }

        if (!Zend_Validate::is($data->getComment(), 'NotEmpty')) {
            $errors[] = $helper->__('Comment can\'t be empty');
        }

        if (!Zend_Validate::is($data->getPostId(), 'NotEmpty')) {
            $errors[] = $helper->__('post_id can\'t be empty');
        }

        $validator = new Zend_Validate_EmailAddress();
        if (!$validator->isValid($data->getEmail())) {
            $errors[] = $helper->__('"%s" is not a valid email address.', $data->getEmail());
        }

        return $errors;
    }

    public function viewAction()
    {
        $identifier = $this->getRequest()->getParam('identifier', $this->getRequest()->getParam('id', false));

        $helper = Mage::helper('blog');
        $session = Mage::getSingleton('customer/session');

        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('blog/comment');
            $data['user'] = strip_tags($data['user']);
            $model->setData($data);

            if (!Mage::getStoreConfig('blog/comments/enabled')) {
                $session->addError($helper->__('Comments are not enabled.'));
                if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
                    $this->_forward('NoRoute');
                }
                return;
            }

            if (!$session->isLoggedIn() && Mage::getStoreConfig('blog/comments/login')) {
                $session->addError($helper->__('You must be logged in to comment.'));
                if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
                    $this->_forward('NoRoute');
                }
                return;
            } else {
                if ($session->isLoggedIn() && Mage::getStoreConfig('blog/comments/login')) {
                    $model->setUser($helper->getUserName());
                    $model->setEmail($helper->getUserEmail());
                }
            }

            try {
                if (Mage::getStoreConfig('blog/recaptcha/enabled') && !$session->isLoggedIn()) {
                    $publickey = Mage::getStoreConfig('blog/recaptcha/publickey');
                    $privatekey = Mage::getStoreConfig('blog/recaptcha/privatekey');

                    $resp = recaptcha_check_answer(
                        $privatekey, $_SERVER["REMOTE_ADDR"], $data["recaptcha_challenge_field"],
                        $data["recaptcha_response_field"]
                    );

                    if (!$resp->is_valid) {
                        if ($resp->error == "incorrect-captcha-sol") {
                            $session->addError($helper->__('Your Recaptcha solution was incorrect, please try again'));
                        } else {
                            $session->addError($helper->__('An error occured. Please try again'));
                        }
                        // Redirect back with error message
                        $session->setBlogPostModel($model);
                        $this->_redirectReferer();
                        return;
                    }
                }

                $errors = $this->_validateData($model);
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        $session->addError($error);
                    }
                    $this->_redirectReferer();
                    return;
                }

                if ($session->getData('blog_post_model')) {
                    $session->unsetData('blog_post_model');
                }
                $model->setCreatedTime(now());
                $model->setComment(htmlspecialchars($model->getComment(), ENT_QUOTES));
                if (Mage::getStoreConfig('blog/comments/approval')) {
                    $model->setStatus(2);
                    $session->addSuccess($helper->__('Your comment has been submitted.'));
                } else {
                    if ($session->isLoggedIn() && Mage::getStoreConfig('blog/comments/loginauto')) {
                        $model->setStatus(2);
                        $session->addSuccess($helper->__('Your comment has been submitted.'));
                    } else {
                        $model->setStatus(1);
                        $session->addSuccess($helper->__('Your comment has been submitted and is awaiting approval.'));
                    }
                }
                $model->save();

                $commentId = $model->getCommentId();
            } catch (Exception $e) {
                if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
                    $this->_forward('NoRoute');
                }
            }

            if (Mage::getStoreConfig('blog/comments/recipient_email') != null && $model->getStatus() == 1
                && isset($commentId)
            ) {
                $translate = Mage::getSingleton('core/translate');
                /* @var $translate Mage_Core_Model_Translate */
                $translate->setTranslateInline(false);
                try {
                    $data["url"] = Mage::getUrl('blog/manage_comment/edit/id/' . $commentId);
                    $postObject = new Varien_Object();
                    $postObject->setData($data);
                    $mailTemplate = Mage::getModel('core/email_template');
                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                        ->sendTransactional(
                            Mage::getStoreConfig('blog/comments/email_template'),
                            Mage::getStoreConfig('blog/comments/sender_email_identity'),
                            Mage::getStoreConfig('blog/comments/recipient_email'), null, array('data' => $postObject)
                        );
                    $translate->setTranslateInline(true);
                } catch (Exception $e) {
                    $translate->setTranslateInline(true);
                }
            }
            $this->_redirectReferer();
            return;
            if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
                $this->_forward('NoRoute');
            }
        } else {
            /* GET request */
            if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
                $session->addNotice($helper->__('The requested page could not be found'));
                $this->_redirect($helper->getRoute());
                return false;
            }
        }
    }

    public function noRouteAction($coreRoute = null)
    {
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
        $this->getResponse()->setHeader('Status', '404 File not found');

        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultNoRoute');
        }
    }
}