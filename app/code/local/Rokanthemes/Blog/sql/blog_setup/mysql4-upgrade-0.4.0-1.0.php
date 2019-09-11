<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
try {
    $installer->run("
        CREATE TABLE {$this->getTable('blog/blog')} LIKE {$this->getTable('blog/lblog')};
        INSERT {$this->getTable('blog/blog')} SELECT * FROM {$this->getTable('blog/lblog')};

        CREATE TABLE {$this->getTable('blog/comment')} LIKE {$this->getTable('blog/lcomment')};
        INSERT {$this->getTable('blog/comment')} SELECT * FROM {$this->getTable('blog/lcomment')};

        CREATE TABLE {$this->getTable('blog/cat')} LIKE {$this->getTable('blog/lcat')};
        INSERT {$this->getTable('blog/cat')} SELECT * FROM {$this->getTable('blog/lcat')};

        CREATE TABLE {$this->getTable('blog/post_cat')} LIKE {$this->getTable('blog/lpost_cat')};
        INSERT {$this->getTable('blog/post_cat')} SELECT * FROM {$this->getTable('blog/lpost_cat')};

        CREATE TABLE {$this->getTable('blog/store')} LIKE {$this->getTable('blog/lstore')};
        INSERT {$this->getTable('blog/store')} SELECT * FROM {$this->getTable('blog/lstore')};

        CREATE TABLE {$this->getTable('blog/cat_store')} LIKE {$this->getTable('blog/lcat_store')};
        INSERT {$this->getTable('blog/cat_store')} SELECT * FROM {$this->getTable('blog/lcat_store')};

        ALTER TABLE {$this->getTable('blog/blog')} ADD `tags` TEXT NOT NULL;
        ALTER TABLE {$this->getTable('blog/blog')} ADD `short_content` TEXT NOT NULL;
    ");
} catch (Exception $e) {
    Mage::logException($e);
}

try {
    $installer->run("
        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/tag')} (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `tag` VARCHAR(255) NOT NULL ,
            `tag_count` INT(11) NOT NULL DEFAULT 0,
            `store_id` TINYINT(4) NOT NULL ,
            INDEX ( `tag`, `count`, `store_id` )
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;
    ");
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();