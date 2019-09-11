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
 * Method collection resource model
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Model_Resource_Method_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('ultimate_shipper/method');
    }

    /**
     * get methods as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author RSMD Partners
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='method_name', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author RSMD Partners
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='method_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author RSMD Partners
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
