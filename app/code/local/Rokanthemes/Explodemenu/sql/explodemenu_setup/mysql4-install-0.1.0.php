<?php

$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_category', 'cat_label', array(
    'group'         => 'General Information',
	'label'			=> 'Category Label',
	'note'			=> "Labels for Level Top Category",
    'input'         => 'select',
    'type'          => 'varchar',
	'source'		=> 'explodemenu/config_source_category_label',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
	'global'		=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->endSetup(); 