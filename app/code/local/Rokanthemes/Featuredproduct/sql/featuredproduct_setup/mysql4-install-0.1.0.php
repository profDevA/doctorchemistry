<?php
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 'featured', array(
	'label' => 'Featured',
	'type' => 'int',
	'input' => 'select',
	'source' => 'eav/entity_attribute_source_boolean',
	'visible' => true,
	'required' => false,
	'position' => 10,
));
