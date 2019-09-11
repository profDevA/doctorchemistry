<?php
/**
 * Description of Numbers
 *
 * @author Rezoanul Alam @ www.SaveTheMage.com
 */
class SaveTheMage_CustomInvoiceAndOrderNumber_Model_Numbers {
    public function getOrderNumber($storeId)
    {
        return $this->_returnIncrement_last_id('order', $storeId);
    }
    
    public function getInvoiceNumber($storeId)
    {
        return $this->_returnIncrement_last_id('invoice', $storeId);
    }
    
    private function _returnIncrement_last_id($entity_type_code, $storeId)
    {
        $prefix = Mage::getConfig()->getTablePrefix();
        $sql ="select increment_last_id from ". $prefix ."eav_entity_store ees 
        inner join ". $prefix ."eav_entity_type eet 
        on ees.entity_type_id = eet.entity_type_id  and eet.entity_type_code = '" . $entity_type_code . "'";
        
        if( $storeId > -1 )
        {
            $sql .=' where ees.store_id = ' . $storeId;
        }
        
        $sql .=' limit 0, 1 ';
        
        $inc_last_id = "";
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $results = $read->fetchAll( $sql );
        foreach( $results as $arr_row)
        {
            $inc_last_id = $arr_row['increment_last_id'];
        }
        
        return $inc_last_id;
    }
    
    private function _increment_last_id_exist($entity_type_code, $storeId)
    {
        $prefix = Mage::getConfig()->getTablePrefix();
        $sql ="select increment_last_id from ". $prefix ."eav_entity_store ees 
        inner join ". $prefix ."eav_entity_type eet 
        on ees.entity_type_id = eet.entity_type_id  and eet.entity_type_code = '" . $entity_type_code . "'";
        
        if( $storeId > -1 )
        {
            $sql .=' where ees.store_id = ' . $storeId;
        }
        
        $sql .=' limit 0, 1 ';
        
        
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $results = $read->fetchAll( $sql );
        if( count( $results ) >0 ){
            return true;
        }
        
        return false;
    }
    
    public function saveNumber($storeId, $number, $entity_type_code = 'order')
    {
        $sql = "";
        
        $prefix = Mage::getConfig()->getTablePrefix();
        
        if( $this->_increment_last_id_exist($entity_type_code, $storeId) ){
            $sql = "update ". $prefix ."eav_entity_store ees 
            inner join ". $prefix ."eav_entity_type eet on ees.entity_type_id = eet.entity_type_id and eet.entity_type_code = '". $entity_type_code ."'
            set ees.increment_last_id = '" . $number ."'
            where ees.store_id = " . $storeId;
        }
        else{
            $sql = "SELECT entity_type_id FROM ". $prefix ."eav_entity_type WHERE entity_type_code='" . $entity_type_code ."'";
            
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $results = $read->fetchAll( $sql );
            $entity_type_id = "";
            foreach( $results as $arr_row)
            {
                $entity_type_id = $arr_row['entity_type_id'];
            }
        
            $sql =" INSERT INTO ". $prefix ."eav_entity_store (entity_type_id, store_id, increment_prefix, increment_last_id) 
                VALUES(". $entity_type_id .", ". $storeId .", 1, '". $number  ."')";
        }
        try{
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');        
            $write->query( $sql );
        }
        catch(Exception $ex){ }
    }
    
}

?>
