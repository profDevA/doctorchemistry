<?php

class Rokanthemes_Blog_Manage_CommentController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/blog/comment');
    }

    protected function _initAction()
    {
        $this
            ->loadLayout()
            ->_setActiveMenu('blog/comment')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Comment Manager'), Mage::helper('adminhtml')->__('Comment Manager')
            );
        $this->displayTitle('Comments');

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('blog/comment');
                $model
                    ->setId($this->getRequest()->getParam('id'))
                    ->delete()
                ;

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Comment was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function approveAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('blog/comment');

                $model
                    ->setId($this->getRequest()->getParam('id'))
                    ->setStatus(2)
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Comment was approved')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function unapproveAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('blog/comment');

                $model->setId($this->getRequest()->getParam('id'))
                    ->setStatus(1)
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Comment was unapproved')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $blogIds = $this->getRequest()->getParam('comment');
        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('adminhtml')->__('Please select comment(s)')
            );
        } else {
            try {
                foreach ($blogIds as $blogId) {
                    $blog = Mage::getModel('blog/comment')->load($blogId);
                    $blog->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d comments(s) were successfully deleted', count($blogIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function massApproveAction()
    {
        $blogIds = $this->getRequest()->getParam('comment');
        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select comment(s)'));
        } else {
            try {
                foreach ($blogIds as $blogId) {
                    $blog = Mage::getSingleton('blog/comment')
                        ->load($blogId)
                        ->setStatus(2)
                        ->setIsMassupdate(true)
                        ->save()
                    ;
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d comment(s) were successfully approved', count($blogIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function massUnapproveAction()
    {
        $blogIds = $this->getRequest()->getParam('comment');

        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select comment(s)'));
        } else {
            try {
                foreach ($blogIds as $blogId) {
                    $blog = Mage::getSingleton('blog/comment')
                        ->load($blogId)
                        ->setStatus(1)
                        ->setIsMassupdate(true)
                        ->save()
                    ;
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d comment(s) were successfully unapproved', count($blogIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('blog/comment')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('blog_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('blog/posts');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('blog/manage_comment_edit'));
            $this->displayTitle('Edit comment');

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Post does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('blog/comment');
            $model
                ->setData($data)
                ->setId($this->getRequest()->getParam('id'))
            ;

            try {
                if ($model->getCreatedTime == null || $model->getUpdateTime() == null) {
                    $model
                        ->setCreatedTime(now())
                        ->setUpdateTime(now())
                    ;
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('blog')->__('Comment was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Unable to find comment to save'));
        $this->_redirect('*/*/');
    }

    protected function displayTitle($data = null, $root = 'Blog')
    {
        if (!Mage::helper('blog')->magentoLess14()) {
            if ($data) {
                if (!is_array($data)) {
                    $data = array($data);
                }
                foreach ($data as $title) {
                    $this->_title($this->__($title));
                }
                $this->_title($this->__($root));
            } else {
                $this->_title($this->__('Blog'))->_title($root);
            }
        }
        return $this;
    }
}