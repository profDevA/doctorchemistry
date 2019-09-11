<?php
class Databunch_Ems_Model_Resource_Ems_Region extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('ems/ems_region', 'region_id');
    }
}