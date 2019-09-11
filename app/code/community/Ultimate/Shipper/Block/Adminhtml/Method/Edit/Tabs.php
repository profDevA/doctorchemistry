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
 * Method admin edit tabs
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Block_Adminhtml_Method_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author RSMD Partners
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('method_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ultimate_shipper')->__('Method'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Edit_Tabs
     * @author RSMD Partners
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_method',
            array(
                'label'   => Mage::helper('ultimate_shipper')->__('Method'),
                'title'   => Mage::helper('ultimate_shipper')->__('Method'),
                'content' => $this->getLayout()->createBlock(
                    'ultimate_shipper/adminhtml_method_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve method entity
     *
     * @access public
     * @return Ultimate_Shipper_Model_Method
     * @author RSMD Partners
     */
    public function getMethod()
    {
        return Mage::registry('current_method');
    }
}
