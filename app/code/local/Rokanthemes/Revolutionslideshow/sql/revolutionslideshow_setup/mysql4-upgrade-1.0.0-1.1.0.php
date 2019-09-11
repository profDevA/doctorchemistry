<?php
$installer = $this;
$connection = $installer->getConnection();
$installer->startSetup();
$installer->getConnection()
	->addColumn($installer->getTable('revolutionslideshow/revolution_slides'),
		'slide_bg',
		'VARCHAR(16) NOT NULL '
	);
$installer->endSetup();