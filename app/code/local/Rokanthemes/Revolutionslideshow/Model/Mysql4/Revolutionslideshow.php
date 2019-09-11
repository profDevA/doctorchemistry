<?php


class Rokanthemes_Revolutionslideshow_Model_Mysql4_Revolutionslideshow extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the slide_id refers to the key field in your database table.
        $this->_init('revolutionslideshow/slides', 'slide_id');
    }

	/**
	 * add slide to store
     *
     * @param Mage_Core_Model_Abstract $object
	 * @return Mage_Core_Model_Abstract $object
     */
	protected function _afterSave(Mage_Core_Model_Abstract $object)
	{
		$condition = $this->_getWriteAdapter()->quoteInto('slide_id = ?', $object->getId());
		$this->_getWriteAdapter()->delete($this->getTable('revolutionslideshow/slides_store'), $condition);

		if(!$object->getData('stores')){
			$object->setData('stores',$object->getData('store_id'));
		}

		if(in_array(0,$object->getData('stores'))){
			$object->setData('stores',array(0));
		}

		foreach ((array)$object->getData('stores') as $store) {
			$storeArray = array();
			$storeArray['slide_id'] = $object->getId();
			$storeArray['store_id'] = $store;
			$this->_getWriteAdapter()->insert($this->getTable('revolutionslideshow/slides_store'), $storeArray);
		}

		return parent::_afterSave($object);
	}

	/**
     * add store data to slide
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Abstract $object
     */
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
	{
		// Liste des stores
		$select = $this->_getReadAdapter()->select()
			->from($this->getTable('revolutionslideshow/slides_store'))
			->where('slide_id = ?', $object->getId());

		if ($data = $this->_getReadAdapter()->fetchAll($select)) {
			$storesArray = array();
			foreach ($data as $row) {
				$storesArray[] = $row['store_id'];
			}
			$object->setData('store_id', $storesArray);
		}

		return parent::_afterLoad($object);
	}

}