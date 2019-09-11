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
 * Method edit form
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Block_Adminhtml_Method_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Edit_Form
     * @author RSMD Partners
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/*/save',
                    array(
                        'id' => $this->getRequest()->getParam('id')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
