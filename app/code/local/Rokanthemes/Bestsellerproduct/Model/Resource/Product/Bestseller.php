<?php
class Rokanthemes_Bestsellerproduct_Model_Resource_Product_Bestseller extends Mage_Reports_Model_Resource_Product_Collection
{

 	public function addOrderedQty($from = '', $to = '')  // rewirte method addOrderedQty
 	{
	  	$adapter = $this->getConnection();
	   	$orderTableAliasName = $adapter->quoteIdentifier('order');
	    $orderJoinCondition = array( 
						    		$orderTableAliasName . '.entity_id = order_items.order_id',
						    		$adapter->quoteInto("{$orderTableAliasName}.state <> ?", 
						    		Mage_Sales_Model_Order::STATE_CANCELED),
	    						);
	     $productJoinCondition = array(
								      	'e.entity_id = T1.final_product_id', 
								      	$adapter->quoteInto('e.entity_type_id = ?', 
								      	$this->getProductEntityTypeId()) 
	      							);
	    if ($from != '' && $to != '') 
	    {
	       	$fieldName = $orderTableAliasName . '.created_at';
	       	$orderJoinCondition[] = $this->_prepareBetweenSql($fieldName, $from, $to);
	    }
	    $subSelect = $this->getSelect()->reset()
	    ->from( array('order_items' => $this->getTable('sales/order_item')),
	     		 array(  	'qty_ordered',
	     					'name',
	      					'final_product_id' => new Zend_Db_Expr(
	      													"IF(parent_id IS NOT NULL AND visibility != " . Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH . ", parent_id, order_items.product_id)"
	      												),
	        		)
	     	)
	    ->joinInner(array('order' => $this->getTable('sales/order')),
	     			implode(' AND ', $orderJoinCondition),
	     			array()
	     	)
	    ->joinLeft( array('cpr' => $this->getTable('catalog/product_relation')),
	     			'cpr.child_id = order_items.product_id',
	     			array( 'parent_id',)
	     	)
	    ->joinLeft( array('cat_index' => $this->getTable('catalog/category_product_index')),
	     			'cat_index.product_id = order_items.product_id',
	      			array( 'store_id', 'visibility', 'category_id', )
	      	)
	    ->where('parent_item_id IS NULL')
	    ->where('cat_index.store_id = ?',
	     		Mage::app()->getStore()->getId()
	     	)
	    ->where('category_id = ?',
	    		Mage::app()->getStore()->getRootCategoryId()
	    	);

	    $subSelectString = '(' . $subSelect->__toString() . ')';
	    $this->getSelect()->reset()
	    	->from( array('T1' => new Zend_Db_Expr($subSelectString)),
	    	  		 array( 'ordered_qty' => 'SUM(qty_ordered)',
	    	  		  		'order_items_name' => 'name',
	    	  		   		'entity_id' => 'final_product_id',
	    	  		    )
	    	  	)
	    	->joinLeft( array('e' => $this->getProductEntityTableName()),
	    	  implode(' AND ', $productJoinCondition),
	    	   array(	'entity_type_id' => 'e.entity_type_id',
	    	    	  	'attribute_set_id' => 'e.attribute_set_id',
	    	     	  	'type_id' => 'e.type_id',
	    	      	  	'sku' => 'e.sku',
	    	        	'has_options' => 'e.has_options',
	    	        	'required_options' => 'e.required_options',
	    	         	'created_at' => 'e.created_at',
	    	          	'updated_at' => 'e.updated_at' )
	    	   	)
	    	->group('final_product_id');

    	return $this;
    }

}