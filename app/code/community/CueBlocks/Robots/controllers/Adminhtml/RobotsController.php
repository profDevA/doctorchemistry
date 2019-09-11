<?php
/**
 *
 * Package :- Robots
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Robots_Adminhtml_RobotsController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->_forward('edit');
		return $this;
	}   
	public function indexAction()
	{
		$this->_initAction();       
		$this->renderLayout();
	}
	public function editAction()
	{
		$cueattributevalueId     = $this->getRequest()->getParam('id');
		if ($cueattributevalueId == 0) 
		{
			$this->loadLayout();
			$this->_setActiveMenu('cueblocks/robots');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock('robots/adminhtml_robots_edit'))
			->_addLeft($this->getLayout()->createBlock('robots/adminhtml_robots_edit_tabs'));
			$this->renderLayout();
		} 
		else 
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('robots')->__('File does not exist'));
			$this->_redirect('*/*/');
		}
	}
	public function newAction()
	{
		$this->_forward('edit');
	}
	public function saveAction()
	{
		$store_id = $this->getRequest()->getParam('store');
		$store_path = '';
		if($store_id) {
			$store_path = Mage::getStoreConfig('robotstxt/general/path_map', $store_id);
		}
		$io = new Varien_Io_File();
		if($store_path) {
			$path = $io->getCleanPath(Mage::getBaseDir() . DS . $store_path . DS);
		} else {
			$path = $io->getCleanPath(Mage::getBaseDir() . DS);
		}
		$filepath= $path.'robots.txt';
		$folderwrite=is_writable($path); 
		$write=is_writable($filepath);
		if (file_exists($filepath)):
			if($folderwrite):
				if($write):
					$content=$this->getRequest()->getParam('content');
					$create = fopen($filepath, "w");
					file_put_contents($filepath, $content);
					$close = fclose($create); //closes our file
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('robots')->__('File saved successfully'));
					if($store_id) {
						$this->_redirect('*/*/', array('store' => $store_id));
					} else {
						$this->_redirect('*/*/');
					}
				else:
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('robots')->__('File needs writable permissions'));
					if($store_id) {
						$this->_redirect('*/*/', array('store' => $store_id));
					} else {
						$this->_redirect('*/*/');
					}
				endif;
			else:
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('robots')->__('Folder needs writable permissions to create robots.txt'));
				if($store_id) {
					$this->_redirect('*/*/', array('store' => $store_id));
				} else {
					$this->_redirect('*/*/');
				}
			endif; 
		else:
		    if($folderwrite):
					$content=$this->getRequest()->getParam('content');
					$create = fopen($filepath, "w");
					file_put_contents($filepath, $content);
					$close = fclose($create); //closes our file
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('robots')->__('File saved successfully'));
					if($store_id) {
						$this->_redirect('*/*/', array('store' => $store_id));
					} else {
						$this->_redirect('*/*/');
					}
				
			else:
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('robots')->__('Folder needs writable permissions to create robots.txt'));
				if($store_id) {
					$this->_redirect('*/*/', array('store' => $store_id));
				} else {
					$this->_redirect('*/*/');
				}
			endif; 
		endif;
	}
}
