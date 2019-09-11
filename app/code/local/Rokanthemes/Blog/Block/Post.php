<?php

class Rokanthemes_Blog_Block_Post extends Rokanthemes_Blog_Block_Abstract
{
    public function getPost()
    {
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                $post = Mage::getModel('blog/post')->load($this->getPostId());
            } else {
                $post = Mage::getSingleton('blog/post');
            }
            $category = Mage::getSingleton('blog/cat')->load(
                $this->getRequest()->getParam(self::$_catUriParam), "identifier"
            );
            if ($category->getIdentifier()) {
                $post->setAddress(
                    $this->getBlogUrl(
                        null,
                        array(
                            self::$_catUriParam  => $category->getIdentifier(),
                            self::$_postUriParam => $post->getIdentifier()
                        )
                    )
                );
            } else {
                $post->setAddress($this->getBlogUrl($post->getIdentifier()));
            }

            $this->_prepareData($post)->_prepareDates($post);

            $this->setData('post', $post);
        }

        return $this->getData('post');
    }

    public function getBookmarkHtml($post)
    {
        if ($this->_helper()->isBookmarksPost()) {
            return $this->setTemplate('rokanthemes_blog/bookmark.phtml')->setPost($post)->renderView();
        }
    }

    public function getComment()
    {
        if (!$this->hasData('commentCollection')) {
            $collection = Mage::getModel('blog/comment')
                ->getCollection()
                ->addPostFilter($this->getPost()->getPostId())
                ->setOrder('created_time', 'DESC')
                ->addApproveFilter(2)
            ;
            $collection->setPageSize((int)Mage::helper('blog')->commentsPerPage());
            $this->setData('commentCollection', $collection);
        }
        return $this->getData('commentCollection');
    }

    public function getCommentsEnabled()
    {
        return Mage::getStoreConfig('blog/comments/enabled');
    }

    public function getLoginRequired()
    {
        return Mage::getStoreConfig('blog/comments/login');
    }

    public function getFormAction()
    {
        return $this->getUrl('*/*/*');
    }

    public function getFormData()
    {
        return $this->getRequest();
    }

    protected function _prepareLayout()
    {
        $this->_prepareCrumbs()->_prepareHead();
    }

    protected function _beforeToHtml()
    {
        Mage::helper('blog/toolbar')->create(
            $this,
            array(
                 'orders'        => array('created_time' => $this->__('Created At'), 'email' => $this->__('Added By')),
                 'default_order' => 'created_time',
                 'dir'           => 'desc',
                 'limits'        => self::$_helper->commentsPerPage(),
                 'method'        => 'getComment'
            )
        );
        return $this;
    }

    protected function _prepareCrumbs()
    {
        $breadcrumbs = $this->getCrumbs();
        if ($breadcrumbs) {
            $helper = $this->_helper();
            $breadcrumbs->addCrumb(
                'blog',
                array(
                     'label' => $helper->getTitle(),
                     'title' => $this->__('Return to %s', $helper->getTitle()),
                     'link'  => Mage::getUrl($helper->getRoute()),
                )
            );

            $title = trim($this->getCategory()->getTitle());
            if ($title) {
                $breadcrumbs->addCrumb(
                    'cat',
                    array(
                         'label' => $title,
                         'title' => $this->__('Return to %s', $title),
                         'link'  => Mage::getUrl(
                             $helper->getRoute(), array('cat' => $this->getCategory()->getIdentifier())
                         ),
                    )
                );
            }

            $breadcrumbs->addCrumb(
                'blog_page', array('label' => htmlspecialchars_decode($this->getPost()->getTitle()))
            );
        }

        return $this;
    }

    protected function getCategory()
    {
        if (!$this->hasData('postCategory')) {
            $this->setData(
                'postCategory', Mage::getSingleton('blog/cat')->load($this->getRequest()->getParam('cat'), "identifier")
            );
        }

        return $this->getData('postCategory');
    }

    protected function _prepareHead()
    {
        parent::_prepareMetaData($this->getPost());

        return $this;
    }

    public function setCommentDetails($name, $email, $comment)
    {
        return $this
            ->setData('commentName', $name)
            ->setData('commentEmail', $email)
            ->setData('commentComment', $comment)
        ;
    }

    public function getCommentText()
    {
        $blogPostModelFromSession = Mage::getSingleton('customer/session')->getBlogPostModel();
        if ($blogPostModelFromSession) {
            return $blogPostModelFromSession->getComment();
        }

        if (!empty($this->_data['commentComment'])) {
            return $this->_data['commentComment'];
        }
        return;
    }

    public function getCommentEmail()
    {
        $blogPostModelFromSession = Mage::getSingleton('customer/session')->getBlogPostModel();
        if ($blogPostModelFromSession) {
            return $blogPostModelFromSession->getEmail();
        }

        if (!empty($this->_data['commentEmail'])) {
            return $this->_data['commentEmail'];
        } elseif ($customer = Mage::getSingleton('customer/session')->getCustomer()) {
            return $customer->getEmail();
        }
        return;
    }

    public function getCommentName()
    {
        $blogPostModelFromSession = Mage::getSingleton('customer/session')->getBlogPostModel();

        $name = null;
        if ($blogPostModelFromSession) {
            $name = $blogPostModelFromSession->getUser();
        }
        if (!empty($this->_data['commentName'])) {
            $name = $this->_data['commentName'];
        } elseif ($customer = Mage::getSingleton('customer/session')->getCustomer()) {
            $name = $customer->getName();
        }
        return trim($name);
    }
}