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
 * Method admin block
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Block_Adminhtml_Method extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_method';
        $this->_blockGroup         = 'ultimate_shipper';
        parent::__construct();
        $this->_headerText         = Mage::helper('ultimate_shipper')->__('Method');
        $this->_updateButton('add', 'label', Mage::helper('ultimate_shipper')->__('Add Method'));

    }
}
