<?php
/**
 * Ultimate_Shipper extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Shipping & Fulfillment
 * @package        Ultimate_Shipper
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Shipper module install script
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('ultimate_shipper/method'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Method ID'
    )
    ->addColumn(
        'method_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Method Name'
    )
    ->addColumn(
        'method_identifier',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Method Identifier'
    )
    ->addColumn(
        'method_type',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable'  => true,
            'default'   => null,
        ),
        'Method Type'
    )    
    ->addColumn(
        'method_rate',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,'12,2',
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Method Rate'
    )
    ->addColumn(
        'handling_type',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable'  => true,
            'default'   => null,
        ),
        'Handling Type'
    )
    ->addColumn(
        'handling_action',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable'  => true,
            'default'   => null,
        ),
        'Handling Applied'
    )   
    ->addColumn(
        'handling_fee',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,'12,2',
        array(
            'nullable'  => true,
            'default'   => '0',
        ),
        'Handling Fee'
    )
    ->addColumn(
        'show_method',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => true,
            'default'   => '0',
        ),
        'Show Method If Not Applicable'
    )
    ->addColumn(
        'error_text',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        array(
            'nullable'  => true,
            'default'   => null,
        ),
        'Displayed Error Message'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Method Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Method Creation Time'
    ) 
    ->setComment('Ultimate Flat Rate Shipping Method Table');
$this->getConnection()->createTable($table);
$this->endSetup();
