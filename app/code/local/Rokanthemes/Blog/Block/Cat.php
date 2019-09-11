<?php

class Rokanthemes_Blog_Block_Cat extends Rokanthemes_Blog_Block_Abstract
{
    public function getPosts()
    {
        $category = $this->getCategory();

        if (!$category->getCatId()) {
            return false;
        }
        $collection = parent::_prepareCollection()->addCatFilter($category->getCatId());
        parent::_processCollection($collection, $category);
        return $collection;
    }

    public function getCategory()
    {
        return Mage::getSingleton('blog/cat');
    }

    protected function _prepareLayout()
    {
        $post = $this->getCategory();
        $breadcrumbs = $this->getCrumbs();
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb(
                'blog',
                array(
                     'label' => self::$_helper->getTitle(),
                     'title' => $this->__('Return to %s', self::$_helper->getTitle()),
                     'link'  => $this->getBlogUrl(),
                )
            );
            $breadcrumbs->addCrumb('blog_page', array('label' => $post->getTitle(), 'title' => $post->getTitle()));
        }

        parent::_prepareMetaData($post);
    }

}
