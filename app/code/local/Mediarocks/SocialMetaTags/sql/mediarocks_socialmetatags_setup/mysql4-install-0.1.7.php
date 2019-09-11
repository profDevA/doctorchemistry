<?php
/**
 * Media Rocks GbR
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA that is bundled with 
 * this package in the file MEDIAROCKS-LICENSE-COMMUNITY.txt.
 * It is also available through the world-wide-web at this URL:
 * http://solutions.mediarocks.de/MEDIAROCKS-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package is designed for Magento COMMUNITY edition. 
 * Media Rocks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Media Rocks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please send an email to support@mediarocks.de
 *
 */

$installer = $this;
$installer->startSetup();

// get model
$model=Mage::getModel('eav/entity_setup','core_setup');


// create category attributes
$model->addAttribute('catalog_category', 'fb_share_image', array(
    'group'         => 'Social Meta Information',
    'input'         => 'image',
    'type'          => 'varchar',
    'label'         => 'Facebook Share Image',
    'backend'       => 'catalog/category_attribute_backend_image',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'    => 10,
));
$model->addAttribute('catalog_category','facebook_meta_title', array (
    'group'             => 'Social Meta Information',
    'type'              => 'text',
    'input'             => 'text',
    'label'             => 'Facebook Title',
    'required'          => 0,
    'default'           => '',
    'user_defined'      => 1,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 20,
));
$model->addAttribute('catalog_category','facebook_meta_description', array (
    'group'             => 'Social Meta Information',
    'type'              => 'text',
    'input'             => 'textarea',
    'label'             => 'Facebook Description',
    'required'          => 0,
    'default'           => '',
    'user_defined'      => 1,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 30,
));


$model->addAttribute('catalog_category', 'twitter_share_image', array(
    'group'         => 'Social Meta Information',
    'input'         => 'image',
    'type'          => 'varchar',
    'label'         => 'Twitter Card Image',
    'backend'       => 'catalog/category_attribute_backend_image',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'    => 50,
));
$model->addAttribute('catalog_category','twitter_meta_title', array (
    'group'             => 'Social Meta Information',
    'type'              => 'text',
    'input'             => 'text',
    'label'             => 'Twitter Title',
    'required'          => 0,
    'default'           => '',
    'user_defined'      => 1,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 60,
));
$model->addAttribute('catalog_category','twitter_meta_description', array (
    'group'             => 'Social Meta Information',
    'type'              => 'text',
    'input'             => 'textarea',
    'label'             => 'Twitter Description',
    'required'          => 0,
    'default'           => '',
    'user_defined'      => 1,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 70,
));


// create product attributes
$model->addAttribute('catalog_product','fb_share_image', array (
    'group'             => 'Images',
    'type'              => 'varchar',
    'frontend'          => 'catalog/product_attribute_frontend_image',
    'label'             => 'Facebook Share Image',
    'input'             => 'media_image',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'          => false,
    'user_defined'      => 1,
    'default'           => '',
));
$model->addAttribute('catalog_product','facebook_meta_title', array (
    'group'             => 'Meta Information',
    'type'              => 'text',
    'input'             => 'text',
    'label'             => 'Facebook Title',
    'required'          => 0,
    'default'           => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 10,
));
$model->addAttribute('catalog_product','facebook_meta_description', array (
    'group'             => 'Meta Information',
    'type'              => 'text',
    'input'             => 'textarea',
    'label'             => 'Facebook Description',
    'required'          => 0,
    'default'           => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 20,
));


$model->addAttribute('catalog_product','twitter_share_image', array (
    'group'             => 'Images',
    'type'              => 'varchar',
    'frontend'          => 'catalog/product_attribute_frontend_image',
    'label'             => 'Twitter Card Image',
    'input'             => 'media_image',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'          => false,
    'user_defined'      => 1,
    'default'           => '',
));
$model->addAttribute('catalog_product','twitter_meta_title', array (
    'group'             => 'Meta Information',
    'type'              => 'text',
    'input'             => 'text',
    'label'             => 'Twitter Title',
    'required'          => 0,
    'default'           => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 50,
));
$model->addAttribute('catalog_product','twitter_meta_description', array (
    'group'             => 'Meta Information',
    'type'              => 'text',
    'input'             => 'textarea',
    'label'             => 'Twitter Description',
    'required'          => 0,
    'default'           => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order'        => 60,
));

// create table-columns for cms pages
$tableName = $installer->getTable('cms_page');
$installer->getConnection()->addColumn($tableName, 'fb_share_image', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension SocialMetaTags'
));
$installer->getConnection()->addColumn($tableName, 'facebook_meta_title', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension SocialMetaTags'
));
$installer->getConnection()->addColumn($tableName, 'facebook_meta_description', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension SocialMetaTags'
));
$installer->getConnection()->addColumn($tableName, 'twitter_share_image', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension SocialMetaTags'
));
$installer->getConnection()->addColumn($tableName, 'twitter_meta_title', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension SocialMetaTags'
));
$installer->getConnection()->addColumn($tableName, 'twitter_meta_description', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension SocialMetaTags'
));

$installer->endSetup();