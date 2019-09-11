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
 * Method admin edit form
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Block_Adminhtml_Method_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        parent::__construct();
        $this->_blockGroup = 'ultimate_shipper';
        $this->_controller = 'adminhtml_method';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('ultimate_shipper')->__('Save Method')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('ultimate_shipper')->__('Delete Method')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('ultimate_shipper')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author RSMD Partners
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_method') && Mage::registry('current_method')->getId()) {
            return Mage::helper('ultimate_shipper')->__(
                "Edit Method '%s'",
                $this->escapeHtml(Mage::registry('current_method')->getMethodName())
            );
        } else {
            return Mage::helper('ultimate_shipper')->__('Add Method');
        }
    }
}
