<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    CREATE TABLE IF NOT EXISTS  {$this->getTable('blog/tag')} (
        `id` int(11) NOT NULL auto_increment,
        `tag` varchar(255) NOT NULL,
        `tag_count` int(11) NOT NULL default 0,
        `store_id` tinyint(4) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `tag` (`tag`, `tag_count`, `store_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();