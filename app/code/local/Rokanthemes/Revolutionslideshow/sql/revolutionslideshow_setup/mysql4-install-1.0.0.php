<?php

$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS `{$this->getTable('revolutionslideshow/slides')}`;
CREATE TABLE `{$this->getTable('revolutionslideshow/slides')}` (
  `slide_id` int(11) unsigned NOT NULL auto_increment,
  `image` varchar(255) NOT NULL default '',
  `title_color` varchar(255) NOT NULL default '',
  `title_bg` varchar(255) NOT NULL default '',
  `title` text NOT NULL default '',
  `link_color` varchar(255) NOT NULL default '',
  `link_bg` varchar(255) NOT NULL default '',
  `link_hover_color` varchar(255) NOT NULL default '',
  `link_hover_bg` varchar(255) NOT NULL default '',
  `link_text` varchar(255) NOT NULL default '',
  `link_href` varchar(255) NOT NULL default '',
  `banner_1_img` varchar(255) NOT NULL default '',
  `banner_1_imgX2` varchar(255) NOT NULL default '',
  `banner_1_href` varchar(255) NOT NULL default '',
  `banner_2_img` varchar(255) NOT NULL default '',
  `banner_2_imgX2` varchar(255) NOT NULL default '',
  `banner_2_href` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `sort_order` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

/**
 * Drop 'slides_store' table
 */
$conn = $installer->getConnection();
$conn->dropTable($installer->getTable('revolutionslideshow/slides_store'));

/**
 * Create table for stores
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('revolutionslideshow/slides_store'))
    ->addColumn('slide_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    'nullable'  => false,
    'primary'   => true,
), 'Slide ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    'unsigned'  => true,
    'nullable'  => false,
    'primary'   => true,
), 'Store ID')
    ->addIndex($installer->getIdxName('revolutionslideshow/slides_store', array('store_id')),
    array('store_id'))
    ->addForeignKey($installer->getFkName('revolutionslideshow/slides_store', 'slide_id', 'revolutionslideshow/slides', 'slide_id'),
    'slide_id', $installer->getTable('revolutionslideshow/slides'), 'slide_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('revolutionslideshow/slides_store', 'store_id', 'core/store', 'store_id'),
    'store_id', $installer->getTable('core/store'), 'store_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Slide To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Assign 'all store views' to existing slides
 */
$installer->run("INSERT INTO {$this->getTable('revolutionslideshow/slides_store')} (`slide_id`, `store_id`) SELECT `slide_id`, 0 FROM {$this->getTable('revolutionslideshow/slides')};");

$installer->run("

DROP TABLE IF EXISTS `{$this->getTable('revolutionslideshow/revolution_slides')}`;
CREATE TABLE `{$this->getTable('revolutionslideshow/revolution_slides')}` (
  `slide_id` int(11) unsigned NOT NULL auto_increment,
  `transition` text NOT NULL default '',
  `masterspeed` text NOT NULL default '',
  `slotamount` text NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `text` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `sort_order` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

/**
 * Drop 'slides_store' table
 */
$conn = $installer->getConnection();
$conn->dropTable($installer->getTable('revolutionslideshow/revolution_slides_store'));

/**
 * Create table for stores
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('revolutionslideshow/revolution_slides_store'))
	->addColumn('slide_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'nullable'  => false,
		'primary'   => true,
	), 'Slide ID')
	->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
	), 'Store ID')
	->addIndex($installer->getIdxName('revolutionslideshow/revolution_slides_store', array('store_id')),
		array('store_id'))
	->addForeignKey($installer->getFkName('revolutionslideshow/revolution_slides_store', 'slide_id', 'revolutionslideshow/revolution_slides', 'slide_id'),
		'slide_id', $installer->getTable('revolutionslideshow/revolution_slides'), 'slide_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->addForeignKey($installer->getFkName('revolutionslideshow/revolution_slides_store', 'store_id', 'core/store', 'store_id'),
		'store_id', $installer->getTable('core/store'), 'store_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->setComment('Slide To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Assign 'all store views' to existing slides
 */
$installer->run("INSERT INTO {$this->getTable('revolutionslideshow/revolution_slides_store')} (`slide_id`, `store_id`) SELECT `slide_id`, 0 FROM {$this->getTable('revolutionslideshow/revolution_slides')};");

$installer->endSetup();

/**
 * add slide data
 */
$data = array(
	1 => '
<div class="caption sfb"  data-x="0" data-y="0" data-speed="900" data-start="400" data-easing="easeOutBack"  ><img src="{{media url="wysiwyg/bag.jpg"}}" alt="" /></div>

<div class="caption sfb athlete_style_medium"  data-x="870" data-y="160" data-speed="900" data-start="600" data-easing="easeOutExpo" style="color:#535353; line-height: 56px;">HOT<BR>YELLOW<BR>SUMMER</div>

<div class="caption sfb" data-x="885" data-y="350" data-speed="900" data-start="700" data-easing="easeOutExpo">
<span class="link" style="color:#535353; background-color:#edd865">SHOP BAGS<span></span></span >
</div>',
	'
<div class="caption sfl"  data-x="0" data-y="0" data-speed="950" data-start="0" data-easing="easeOutExpo"  ><img src="{{media url="wysiwyg/slider_02.jpg"}}" alt="" /></div>
<div class="caption sfr"  data-x="900" data-y="75" data-speed="950" data-start="500" data-easing="easeOutExpo"  ><img src="{{media url="wysiwyg/slider_banner.jpg"}}" alt="" /></div>
',
);

$model = Mage::getModel('revolutionslideshow/rokanthemerevolution');
foreach ( $data as $k => $v ) {
	$model->load($k)
		->setText($v)
		->save();
}