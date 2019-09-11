<?php
/*
 * @author danhtungit2@gmail.com
 * @pagke  Magentothem_Layerednavigationajax
 * @version 1.1.0
 */
?>
<?php
require_once 'Mage/Catalog/controllers/CategoryController.php';

class Rokanthemes_Layerednavigation_CategoryController extends Mage_Catalog_CategoryController {

    public function _construct() {
        parent::_construct();
    }
    
    
    /**
     * override  Category view action
     */
    public function viewAction() {

        $layer_action = $this->getRequest()->getParam('layer_action');
        if (Mage::helper('layerednavigation/data')->isAjax() && $layer_action == 1) {
            $data = array();

            if ($category = $this->_initCatagory()) {
                $design = Mage::getSingleton('catalog/design');
                $settings = $design->getDesignSettings($category);

                // apply custom design
                if ($settings->getCustomDesign()) {
                    $design->applyCustomDesign($settings->getCustomDesign());
                }

                Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

                $update = $this->getLayout()->getUpdate();
                $update->addHandle('default');

                if (!$category->hasChildren()) {
                    $update->addHandle('catalog_category_layered_nochildren');
                }

                $this->addActionLayoutHandles();
                $update->addHandle($category->getLayoutUpdateHandle());
                $update->addHandle('CATEGORY_' . $category->getId());
                $this->loadLayoutUpdates();

                // apply custom layout update once layout is loaded
                if ($layoutUpdates = $settings->getLayoutUpdates()) {
                    if (is_array($layoutUpdates)) {
                        foreach ($layoutUpdates as $layoutUpdate) {
                            $update->addUpdate($layoutUpdate);
                        }
                    }
                }

                $this->generateLayoutXml()->generateLayoutBlocks(); //Generate new blocks
                $leftLayer = $this->getLayout()->getBlock('catalog.leftnav')->toHtml(); 
                $leftLayer = trim($leftLayer);
                $productlist = $this->getLayout()->getBlock('product_list')->toHtml(); 
                $pcount = $this->getLayout()
                            ->getBlockSingleton('catalog/product_list')
                            ->getLoadedProductCollection();
                $data['status'] = 1;
                $data['removeItem'] = Mage::helper('layerednavigation/data')->getJsRemoveItem();
                $data['leftLayer'] = $leftLayer;
                $data['productlist'] = $productlist;
                $data['pcount'] = count($pcount);

                // apply custom layout (page) template once the blocks are generated
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
                $data['status'] = 0;
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($data));
            return;
        } else {

            if ($category = $this->_initCatagory()) {
                $design = Mage::getSingleton('catalog/design');
                $settings = $design->getDesignSettings($category);

                // apply custom design
                if ($settings->getCustomDesign()) {
                    $design->applyCustomDesign($settings->getCustomDesign());
                }

                Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

                $update = $this->getLayout()->getUpdate();
                $update->addHandle('default');

                if (!$category->hasChildren()) {
                    $update->addHandle('catalog_category_layered_nochildren');
                }

                $this->addActionLayoutHandles();
                $update->addHandle($category->getLayoutUpdateHandle());
                $update->addHandle('CATEGORY_' . $category->getId());
                $this->loadLayoutUpdates();

                // apply custom layout update once layout is loaded
                if ($layoutUpdates = $settings->getLayoutUpdates()) {
                    if (is_array($layoutUpdates)) {
                        foreach ($layoutUpdates as $layoutUpdate) {
                            $update->addUpdate($layoutUpdate);
                        }
                    }
                }

                $this->generateLayoutXml()->generateLayoutBlocks();
                // apply custom layout (page) template once the blocks are generated
                if ($settings->getPageLayout()) {
                    $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
                }

                if ($root = $this->getLayout()->getBlock('root')) {
                    $root->addBodyClass('categorypath-' . $category->getUrlPath())
                            ->addBodyClass('category-' . $category->getUrlKey());
                }

                $this->_initLayoutMessages('catalog/session');
                $this->_initLayoutMessages('checkout/session');
                $this->renderLayout();
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
        }
    }

}