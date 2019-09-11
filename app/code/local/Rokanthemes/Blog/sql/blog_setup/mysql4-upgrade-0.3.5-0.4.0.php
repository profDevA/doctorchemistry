<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('blog/lblog')} CHANGE `content` `post_content` TEXT NOT NULL;
");
$installer->endSetup();