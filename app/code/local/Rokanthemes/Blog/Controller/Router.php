<?php

class Rokanthemes_Blog_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $blog = new Rokanthemes_Blog_Controller_Router();
        $front->addRouter('blog', $blog);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $helper = Mage::helper('blog');
        $route = $helper->getRoute();

        /*  redirect if store was changed  */
        $helper->ifStoreChangedRedirect();

        $identifier = $request->getPathInfo();

        if (substr(str_replace("/", "", $identifier), 0, strlen($route)) != $route) {
            return false;
        }

        $identifier = substr_replace($request->getPathInfo(), '', 0, strlen("/" . $route . "/"));
        $identifier = str_replace('.html', '', $identifier);
        $identifier = str_replace('.htm', '', $identifier);

        if ($identifier == '') {
            $request->setModuleName('blog')
                ->setControllerName('index')
                ->setActionName('index');
            return true;
        }

        if (strpos($identifier, '/')) {
            $page = substr($identifier, strpos($identifier, '/') + 1);
        }

        if (substr($identifier, 0, strlen('tag/')) == 'tag/') {
            $identifier = substr_replace($identifier, '', 0, strlen('cat/'));

            if (strpos($identifier, '/page/')) {
                $page = substr($identifier, strpos($identifier, '/page/') + 6);
                $identifier = substr_replace($identifier, '', strpos($identifier, '/page/'), strlen($page) + 6);
            }

            $rss = false;
            if (strpos($identifier, '/rss')) {
                $rss = true;
                $identifier = substr_replace($identifier, '', strpos($identifier, '/rss'), strlen($page) + 4);
            }
            $identifier = str_replace('/', '', $identifier);

            if ($rss) {
                $request->setModuleName('blog')
                    ->setControllerName('rss')
                    ->setActionName('index')
                    ->setParam('tag', $identifier);
                return true;
            } else {

                $identifier = str_replace('/', '', $identifier);
                $request->setModuleName('blog')
                    ->setControllerName('index')
                    ->setActionName('list')
                    ->setParam('tag', $identifier);
                return true;
            }
        } elseif (substr($identifier, 0, strlen('cat/')) == 'cat/') {
            $identifier = substr_replace($identifier, '', 0, strlen('cat/'));

            if (strpos($identifier, '/page/')) {
                $page = substr($identifier, strpos($identifier, '/page/') + 6);
                $identifier = substr_replace($identifier, '', strpos($identifier, '/page/'), strlen($page) + 6);
            }

            if (strpos($identifier, '/post/')) {
                $postident = substr($identifier, strpos($identifier, '/post/') + 6);
                $identifier = substr_replace($identifier, '', strpos($identifier, '/post/'), strlen($postident) + 6);
                $postident = str_replace('/', '', $postident);
            }

            $rss = false;
            if (strpos($identifier, '/rss')) {
                $rss = true;
                $identifier = substr_replace($identifier, '', strpos($identifier, '/rss'), strlen($page) + 4);
            }
            $identifier = str_replace('/', '', $identifier);

            $cat = Mage::getSingleton('blog/cat');
            if (!$cat->load($identifier)->getCatId()) {
                return false;
            }

            if ($rss) {
                $request->setModuleName('blog')
                    ->setControllerName('rss')
                    ->setActionName('index')
                    ->setParam('identifier', $identifier);
            } else {
                if (isset($postident)) {
                    $post = Mage::getSingleton('blog/post');
                    if (!$post->load($postident)->getId()) {
                        return false;
                    }

                    $request->setModuleName('blog')
                        ->setControllerName('post')
                        ->setActionName('view')
                        ->setParam('identifier', $postident)
                        ->setParam('cat', $identifier);
                    return true;
                } else {
                    $request->setModuleName('blog')
                        ->setControllerName('cat')
                        ->setActionName('view')
                        ->setParam('identifier', $identifier);
                    if (isset($page)) {
                        $request->setParam('page', $page);
                    }
                }
            }
            return true;
        } else {
            if (substr($identifier, 0, strlen('page/')) == 'page/') {
                $identifier = substr_replace($identifier, '', 0, strlen('page/'));

                $request->setModuleName('blog')
                    ->setControllerName('index')
                    ->setActionName('index');
                if (isset($page)) {
                    $request->setParam('page', $page);
                }
                return true;
            } else {
                if (substr($identifier, 0, strlen('rss')) == 'rss') {
                    $identifier = substr_replace($identifier, '', 0, strlen('rss/'));

                    $request->setModuleName('blog')
                        ->setControllerName('rss')
                        ->setActionName('index');
                    return true;
                } else {

                    $identifier = str_replace('/', '', $identifier);

                    $post = Mage::getSingleton('blog/post');
                    if (!$post->load($identifier)->getId()) {
                        if (!$post->load($identifier . ".htm")->getId()) {
                            if (!$post->load($identifier . ".html")->getId()) {
                                return false;
                            }
                        }
                    }

                    $request->setModuleName('blog')
                        ->setControllerName('post')
                        ->setActionName('view')
                        ->setParam('identifier', $identifier);
                    if (isset($page)) {
                        $request->setParam('page', $page);
                    }
                    return true;
                }
            }
        }
        return false;
    }
}