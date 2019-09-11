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
 * Method admin controller
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */

/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * @var $model Ultimate_Shipper_Model_Method
 */
$model = Mage::getModel('ultimate_shipper/method');

/**
 * Set up data rows
 */
$dataRows = array(
  array('method_name' => 'Rocket Shipping (Same Day Shipping)','method_identifier' => 'rocket-shipping','method_type' => 'I','method_rate' => '35.15','handling_type' => 'F','handling_action' => 'O','handling_fee' => '5.35','show_method' => '0','error_text' => NULL,'status' => '1'),
  array('method_name' => '1 Hour Express Shipping','method_identifier' => 'one-hour-express-shipping','method_type' => 'O','method_rate' => '20.25','handling_type' => 'F','handling_action' => 'O','handling_fee' => '3.75','show_method' => '1','error_text' => '1 Hour Express Shipping method for selected cities will be available so soon.','status' => '0'),
  array('method_name' => 'Ultra Fast Shipping (2-3 Business Days)','method_identifier' => 'ultra-fast-shipping','method_type' => NULL,'method_rate' => '17.95','handling_type' => 'F','handling_action' => 'O','handling_fee' => '2.75','show_method' => '0','error_text' => NULL,'status' => '1'),
  array('method_name' => 'Free Delivery (7-14 Business Days)','method_identifier' => 'free-ground-shipping','method_type' => 'O','method_rate' => '0.00','handling_type' => NULL,'handling_action' => 'O','handling_fee' => '0.00','show_method' => '0','error_text' => NULL,'status' => '1')
);    

/**
 * Generate method items
 */
foreach ($dataRows as $data) {
    $model->setData($data)->setOrigData()->save();
}
