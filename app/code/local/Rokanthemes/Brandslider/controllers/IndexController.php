<?php
class Rokanthemes_Brandslider_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/brandslider?id=15 
    	 *  or
    	 * http://site.com/brandslider/id/15 	
    	 */
    	/* 
		$brandslider_id = $this->getRequest()->getParam('id');

  		if($brandslider_id != null && $brandslider_id != '')	{
			$brandslider = Mage::getModel('brandslider/brandslider')->load($brandslider_id)->getData();
		} else {
			$brandslider = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($brandslider == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$brandsliderTable = $resource->getTableName('brandslider');
			
			$select = $read->select()
			   ->from($brandsliderTable,array('brandslider_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$brandslider = $read->fetchRow($select);
		}
		Mage::register('brandslider', $brandslider);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}