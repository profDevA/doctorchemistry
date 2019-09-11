<?php


class Rokanthemes_Revolutionslideshow_Adminhtml_RokanthemerevolutionController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('rokanthemes/revolutionslideshow');
    }

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('rokanthemes/revolutionslideshow/revolution_items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Revolution Slide Manager'), Mage::helper('adminhtml')->__('Revolution Slide Manager'));
		
		return $this;
	}
 
	public function indexAction() {
		$this->_initAction()
			->_addContent($this->getLayout()->createBlock('revolutionslideshow/adminhtml_rokanthemerevolution'))
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('revolutionslideshow/rokanthemerevolution')->load($id);

		if ($model->getId() || $id == 0) {

			$this->_initAction();

			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('rokanthemerevolution_data', $model);

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('revolutionslideshow/adminhtml_rokanthemerevolution_edit'))
				->_addLeft($this->getLayout()->createBlock('revolutionslideshow/adminhtml_rokanthemerevolution_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('revolutionslideshow')->__('Slide does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {

			$files = array('image','thumb');
			foreach ($files as $_file) {
				if(isset($_FILES[$_file]['name']) and (file_exists($_FILES[$_file]['tmp_name']))) {
					try {
						$uploader = new Varien_File_Uploader($_file);
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(false);
						$path = Mage::getBaseDir('media') .DS.'rokanthemes'.DS.'revolution'.DS ;
						$result = $uploader->save($path, $_FILES[$_file]['name'] );
					}catch(Exception $e) {
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
						Mage::getSingleton('adminhtml/session')->setFormData($data);
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						return;
					}
					$data[$_file] = 'rokanthemes/revolution/'.$result['file'];
				}
				else {
					// handle delete image
					if(isset($data[$_file]['delete']) && $data[$_file]['delete'] == 1)
						$data[$_file] = '';
					else
						unset($data[$_file]);
				}
			}

			$model = Mage::getModel('revolutionslideshow/rokanthemerevolution');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));

			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('revolutionslideshow')->__('Slide was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('revolutionslideshow')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('revolutionslideshow/rokanthemerevolution');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slide was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $revolutionslideshowIds = $this->getRequest()->getParam('rokanthemerevolution');
        if(!is_array($revolutionslideshowIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select slide(s)'));
        } else {
            try {
                foreach ($revolutionslideshowIds as $revolutionslideshowId) {
                    $revolutionslideshow = Mage::getModel('revolutionslideshow/rokanthemerevolution')->load($revolutionslideshowId);
                    $revolutionslideshow->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($revolutionslideshowIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $revolutionslideshowIds = $this->getRequest()->getParam('rokanthemerevolution');
        if(!is_array($revolutionslideshowIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select slide(s)'));
        } else {
            try {
                foreach ($revolutionslideshowIds as $revolutionslideshowId) {
                    $revolutionslideshow = Mage::getSingleton('revolutionslideshow/rokanthemerevolution')
                        ->load($revolutionslideshowId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($revolutionslideshowIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}