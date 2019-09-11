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
 * Method admin grid block
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Block_Adminhtml_Method_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author RSMD Partners
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('methodGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Grid
     * @author RSMD Partners
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ultimate_shipper/method')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Grid
     * @author RSMD Partners
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('ultimate_shipper')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('ultimate_shipper')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('ultimate_shipper')->__('Enabled'),
                    '0' => Mage::helper('ultimate_shipper')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'method_name',
            array(
                'header'    => Mage::helper('ultimate_shipper')->__('Method Name'),
                'align'     => 'left',
                'index'     => 'method_name',
            )
        );
             
        /**
         * Removed due to adding feature of 
         * Method Type, Handling Applied, Type and Fee.
         *
         * @author RSMD Partners
         */
        /*        
            $this->addColumn(
                'method_identifier',
                array(
                    'header' => Mage::helper('ultimate_shipper')->__('Method Identifier'),
                    'index'  => 'method_identifier',
                    'type'=> 'text',

                )
            );
        */

        $this->addColumn(
            'method_type',
            array(
                'header'  => Mage::helper('ultimate_shipper')->__('Method Type'),
                'index'   => 'method_type',
                'type'    => 'options',
                'options' => array(
                    ''  => Mage::helper('ultimate_shipper')->__('None'),
                    'O' => Mage::helper('ultimate_shipper')->__('Per Order'),
                    'I' => Mage::helper('ultimate_shipper')->__('Per Item'),
                )
            )
        );

        $this->addColumn(
            'method_rate',
            array(
                'header' => Mage::helper('ultimate_shipper')->__('Price'),
                'index'  => 'method_rate',
                'type'   => 'price',
                'width'  => '100px',
                'currency_code' => Mage::app()->getStore()->getCurrentCurrencyCode(),
            )
        );

        $this->addColumn(
            'handling_type',
            array(
                'header'  => Mage::helper('ultimate_shipper')->__('Calculate Handling Fee'),
                'index'   => 'handling_type',
                'type'    => 'options',
                'width'  => '100px',
                'options' => array(
                    ''  => Mage::helper('ultimate_shipper')->__('None'),
                    'F' => Mage::helper('ultimate_shipper')->__('Fixed'),
                    'P' => Mage::helper('ultimate_shipper')->__('Percent'),
                )
            )
        );

        $this->addColumn(
            'handling_action',
            array(
                'header'  => Mage::helper('ultimate_shipper')->__('Handling Applied'),
                'index'   => 'handling_action',
                'type'    => 'options',
                'options' => array(
                    'O' => Mage::helper('ultimate_shipper')->__('Per Order'),
                    'P' => Mage::helper('ultimate_shipper')->__('Per Package'),
                )
            )
        );

        $this->addColumn(
            'handling_fee',
            array(
                'header' => Mage::helper('ultimate_shipper')->__('Handling Fee'),
                'index'  => 'handling_fee',
                'type'   => 'price',
                'width'  => '100px',
                'currency_code' => Mage::app()->getStore()->getCurrentCurrencyCode(),
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('ultimate_shipper')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('ultimate_shipper')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('ultimate_shipper')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('ultimate_shipper')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('ultimate_shipper')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('ultimate_shipper')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('ultimate_shipper')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Grid
     * @author RSMD Partners
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('method');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('ultimate_shipper')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('ultimate_shipper')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('ultimate_shipper')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('ultimate_shipper')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('ultimate_shipper')->__('Enabled'),
                            '0' => Mage::helper('ultimate_shipper')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Ultimate_Shipper_Model_Method
     * @return string
     * @author RSMD Partners
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author RSMD Partners
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Ultimate_Shipper_Block_Adminhtml_Method_Grid
     * @author RSMD Partners
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
