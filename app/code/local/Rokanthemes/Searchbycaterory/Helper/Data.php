<?php
class Rokanthemes_Searchbycaterory_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_SHOW_SUBCATEGORIES                = 'searchbycaterory/settings/show_subcategories';
    const XML_PATH_SUBCATEGORIES_LEVEL                = 'searchbycaterory/settings/subcategories_level';
    const XML_PATH_INDENTATION_TEXT                  = 'searchbycaterory/settings/indentation_text';
    const XML_PATH_SELECT_CATEGORY_ON_CATEGORY_PAGES = 'searchbycaterory/settings/select_category_on_category_pages';

    public function showSubCategories() {
        return Mage::getStoreConfig(self::XML_PATH_SHOW_SUBCATEGORIES);
    }
    
    public function getSubCategoriesLevel() {
        return Mage::getStoreConfig(self::XML_PATH_SUBCATEGORIES_LEVEL);
    }
    
    public function getIndentationText() {
        return Mage::getStoreConfig(self::XML_PATH_INDENTATION_TEXT);
    }

    public function selectCategoryOnCategoryPages() {
        return Mage::getStoreConfig(self::XML_PATH_SELECT_CATEGORY_ON_CATEGORY_PAGES);
    }

    public function getCategoryParamName() {
        return Mage::getModel('catalog/layer_filter_category')->getRequestVar();
    }

    public function getMaximumCategoryLevel() {
        return $this->showSubCategories() ? 3 : 2;
    }

    public function isCategoryPage() {
        return Mage::app()->getFrontController()->getAction() instanceof Mage_Catalog_CategoryController;
    }

    public function isSearchResultsPage() {
        return Mage::app()->getFrontController()->getAction() instanceof Mage_CatalogSearch_ResultController;
    }

}