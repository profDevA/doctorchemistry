<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('blog/lblog')} ADD `comments` TINYINT(11) NOT NULL AFTER `meta_description`;
");
$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('blog/lstore')};
    CREATE TABLE {$this->getTable('blog/lstore')} (
        `post_id` smallint(6) unsigned,
        `store_id` smallint(6) unsigned
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

    DROP TABLE IF EXISTS {$this->getTable('blog/lcat_store')};
    CREATE TABLE {$this->getTable('blog/lcat_store')} (
        `cat_id` smallint(6) unsigned ,
        `store_id` smallint(6) unsigned
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

    DROP TABLE IF EXISTS {$this->getTable('blog/lpost_cat')};
    CREATE TABLE {$this->getTable('blog/lpost_cat')} (
        `cat_id` smallint(6) unsigned ,
        `post_id` smallint(6) unsigned
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;
");
$installer->endSetup();