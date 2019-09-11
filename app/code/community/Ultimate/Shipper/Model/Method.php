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
 * Method model
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Model_Method extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'ultimate_shipper_method';
    const CACHE_TAG = 'ultimate_shipper_method';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ultimate_shipper_method';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'method';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('ultimate_shipper/method');
    }

    /**
     * before save method
     *
     * @access protected
     * @return Ultimate_Shipper_Model_Method
     * @author RSMD Partners
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save method relation
     *
     * @access public
     * @return Ultimate_Shipper_Model_Method
     * @author RSMD Partners
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author RSMD Partners
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
