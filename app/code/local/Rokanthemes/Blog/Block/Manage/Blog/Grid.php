<?php

class Rokanthemes_Blog_Block_Manage_Blog_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('blogGrid');
        $this->setDefaultSort('created_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('blog/blog')->getCollection();
        $store = $this->_getStore();
        if ($store->getId()) {
            $collection->addStoreFilter($store);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'post_id',
            array(
                 'header' => Mage::helper('blog')->__('ID'),
                 'align'  => 'right',
                 'width'  => '50px',
                 'index'  => 'post_id',
            )
        );

        $this->addColumn(
            'title',
            array(
                 'header' => Mage::helper('blog')->__('Title'),
                 'align'  => 'left',
                 'index'  => 'title',
            )
        );

        $this->addColumn(
            'identifier',
            array(
                 'header' => Mage::helper('blog')->__('Identifier'),
                 'align'  => 'left',
                 'index'  => 'identifier',
            )
        );

        $this->addColumn(
            'user',
            array(
                 'header' => Mage::helper('blog')->__('Poster'),
                 'width'  => '150px',
                 'index'  => 'user',
            )
        );

        $this->addColumn(
            'created_time',
            array(
                 'header'    => Mage::helper('blog')->__('Created at'),
                 'index'     => 'created_time',
                 'type'      => 'datetime',
                 'width'     => '120px',
                 'gmtoffset' => true,
                 'default'   => ' -- '
            )
        );

        $this->addColumn(
            'update_time',
            array(
                 'header'    => Mage::helper('blog')->__('Updated at'),
                 'index'     => 'update_time',
                 'width'     => '120px',
                 'type'      => 'datetime',
                 'gmtoffset' => true,
                 'default'   => ' -- '
            )
        );

        $this->addColumn(
            'status',
            array(
                 'header'  => Mage::helper('blog')->__('Status'),
                 'align'   => 'left',
                 'width'   => '80px',
                 'index'   => 'status',
                 'type'    => 'options',
                 'options' => array(
                     1 => Mage::helper('blog')->__('Enabled'),
                     2 => Mage::helper('blog')->__('Disabled'),
                     3 => Mage::helper('blog')->__('Hidden'),
                 ),
            )
        );

        $this->addColumn(
            'action',
            array(
                 'header'    => Mage::helper('blog')->__('Action'),
                 'width'     => '100',
                 'type'      => 'action',
                 'getter'    => 'getId',
                 'actions'   => array(
                     array(
                         'caption' => Mage::helper('blog')->__('Edit'),
                         'url'     => array('base' => '*/*/edit'),
                         'field'   => 'id',
                     )
                 ),
                 'filter'    => false,
                 'sortable'  => false,
                 'index'     => 'stores',
                 'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('post_id');
        $this->getMassactionBlock()->setFormFieldName('blog');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                 'label'   => Mage::helper('blog')->__('Delete'),
                 'url'     => $this->getUrl('*/*/massDelete'),
                 'confirm' => Mage::helper('blog')->__('Are you sure?'),
            )
        );

        $statuses = Mage::getSingleton('blog/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                 'label'      => Mage::helper('blog')->__('Change status'),
                 'url'        => $this->getUrl('*/*/massStatus', array('_current' => true)),
                 'additional' => array(
                     'visibility' => array(
                         'name'   => 'status',
                         'type'   => 'select',
                         'class'  => 'required-entry',
                         'label'  => Mage::helper('blog')->__('Status'),
                         'values' => $statuses,
                     )
                 )
            )
        );
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}