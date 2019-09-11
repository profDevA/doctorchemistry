<?php

if (!class_exists('Rokanthemes_Blog_Block_Product_ToolbarCommon')) {
    if (Mage::helper('blog')->isMobileInstalled()) {
        class Rokanthemes_Blog_Block_Product_ToolbarCommon extends Rokanthemes_Mobile_Block_Catalog_Product_List_Toolbar
        {
        }
    } else {
        class Rokanthemes_Blog_Block_Product_ToolbarCommon extends Mage_Catalog_Block_Product_List_Toolbar
        {
        }
    }
}