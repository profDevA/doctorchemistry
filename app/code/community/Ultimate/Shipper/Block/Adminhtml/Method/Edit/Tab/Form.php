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
 * Method edit form tab
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Block_Adminhtml_Method_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Edit_Tab_Form
     * @author RSMD Partners
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('method_');
        $form->setFieldNameSuffix('method');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'method_form',
            array('legend' => Mage::helper('ultimate_shipper')->__('Method'))
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('ultimate_shipper')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('ultimate_shipper')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('ultimate_shipper')->__('Disabled'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'method_name',
            'text',
            array(
                'label' => Mage::helper('ultimate_shipper')->__('Method Name'),
                'name'  => 'method_name',
                'note'	=> $this->__('Please enter a appropriate method name to identify'),
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'method_identifier',
            'text',
            array(
                'label' => Mage::helper('ultimate_shipper')->__('Method Identifier'),
                'name'  => 'method_identifier',
                'note'	=> $this->__('Use only letters (a-z), numbers (0-9), hypen(-) or underscore(_) in this field, first character should be a letter.'),
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'method_type',
            'select',
            array(
                'label'  => Mage::helper('ultimate_shipper')->__('Type'),
                'name'   => 'method_type',
                'values' => array(
                    array(
                        'value' => '',
                        'label' => Mage::helper('ultimate_shipper')->__('None'),
                    ),                    
                    array(
                        'value' => 'O',
                        'label' => Mage::helper('ultimate_shipper')->__('Per Order'),
                    ),
                    array(
                        'value' => 'I',
                        'label' => Mage::helper('ultimate_shipper')->__('Per Item'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'method_rate',
            'text',
            array(
                'label' => Mage::helper('ultimate_shipper')->__('Price'),
                'name'  => 'method_rate',
                'note'  => $this->__('Use only numeric values in this field. Base currency US Dollars (USD).'),
                'required'  => true,
                'class' => 'required-entry validate-zero-or-greater',

           )
        );

        $fieldset->addField(
            'handling_type',
            'select',
            array(
                'label'  => Mage::helper('ultimate_shipper')->__('Calculate Handling Fee'),
                'name'   => 'handling_type',
                'values' => array(
                    array(
                        'value' => '',
                        'label' => Mage::helper('ultimate_shipper')->__('None'),
                    ),                    
                    array(
                        'value' => 'F',
                        'label' => Mage::helper('ultimate_shipper')->__('Fixed'),
                    ),
                    array(
                        'value' => 'P',
                        'label' => Mage::helper('ultimate_shipper')->__('Percent'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'handling_action',
            'select',
            array(
                'label'  => Mage::helper('ultimate_shipper')->__('Handling Applied'),
                'name'   => 'handling_action',
                'values' => array(                   
                    array(
                        'value' => 'O',
                        'label' => Mage::helper('ultimate_shipper')->__('Per Order'),
                    ),
                    array(
                        'value' => 'P',
                        'label' => Mage::helper('ultimate_shipper')->__('Per Package'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'handling_fee',
            'text',
            array(
                'label' => Mage::helper('ultimate_shipper')->__('Handling Fee'),
                'name'  => 'handling_fee',
                'note'  => $this->__('Use only numeric values in this field. Base currency US Dollars (USD).'),
                'class' => 'validate-zero-or-greater',
           )
        );

        $fieldset->addField(
            'show_method',
            'select',
            array(
                'label'  => Mage::helper('ultimate_shipper')->__('Show Method If Not Applicable'),
                'name'   => 'show_method',
                'note'  => $this->__('Displays the error message only when method status is disabled or at shipping errors.'),
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('ultimate_shipper')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('ultimate_shipper')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'error_text',
            'textarea',
            array(
                'label' => Mage::helper('ultimate_shipper')->__('Displayed Error Message'),
                'name'  => 'error_text',
                'note'  => $this->__('Use only plain text, html tags or not supported. 
                                      Displayed only when show method if not applicable
                                      is enabled.'),
           )
        );

        $formValues = Mage::registry('current_method')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getMethodData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getMethodData());
            Mage::getSingleton('adminhtml/session')->setMethodData(null);
        } elseif (Mage::registry('current_method')) {
            $formValues = array_merge($formValues, Mage::registry('current_method')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
