<?php

class Rokanthemes_Blog_Helper_Toolbar extends Mage_Core_Block_Abstract
{
    public function create($block, $params = array())
    {
        /* prepare global params */
        $this->setToolbarParentBlock($block);
        $this->setToolbarParams($params);

        $toolbar = $this->getToolbarBlock();
        $toolbar->setPost($this);
        $toolbar->setAvailableOrders($params['orders']);
        $toolbar->setDefaultOrder($params['default_order']);


        if (isset($params['dir'])) {
            $toolbar->setDefaultDirection($params['dir']);
        } else {
            $toolbar->setDefaultDirection('ASC');
        }

        if (isset($params['method'])) {
            $toolbar->setCollection($block->{$params['method']}());
        } else {
            $toolbar->setCollection($block->getPreparedCollection());
        }

        $toolbar->disableViewSwitcher();
        $block->setChild('rokanthemes_blog_comments_toolbar', $toolbar);
    }

    public function getAvailLimits()
    {
        $params = $this->getToolbarParams();

        $limits = array();
        if (isset($params['limits'])) {
            foreach (explode(',', $params['limits']) as $limit) {
                $limit = (int)trim($limit);
                if (!$limit) {
                    continue;
                }
                $limits[$limit] = $limit;
            }
        }
        return $limits;
    }

    public function getToolbarBlock()
    {
        $block = $this->getToolbarParentBlock()->getLayout()->getBlock('rokanthemes_blog_list_toolbar');
        if (!$block) {
            return $this->getToolbarParentBlock()->getLayout()->createBlock('blog/product_toolbar', microtime());
        }
        return $block;
    }
}