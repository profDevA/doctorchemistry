<?php

class Rokanthemes_Verticalmenu_Block_Adminhtml_Verticalmenu_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('vmegamenuGrid');
      $this->setDefaultSort('verticalmenu_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('verticalmenu/verticalmenu')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('verticalmenu_id', array(
          'header'    => Mage::helper('verticalmenu')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'verticalmenu_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('verticalmenu')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('verticalmenu')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('verticalmenu')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('verticalmenu')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('verticalmenu')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('verticalmenu')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('verticalmenu')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('verticalmenu_id');
        $this->getMassactionBlock()->setFormFieldName('verticalmenu');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('verticalmenu')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('verticalmenu')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('verticalmenu/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('verticalmenu')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('verticalmenu')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}