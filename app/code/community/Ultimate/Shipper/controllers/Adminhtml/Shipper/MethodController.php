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
 * Method admin controller
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Adminhtml_Shipper_MethodController extends Ultimate_Shipper_Controller_Adminhtml_Shipper
{
    /**
     * init the method
     *
     * @access protected
     * @return Ultimate_Shipper_Model_Method
     */
    protected function _initMethod()
    {
        $methodId  = (int) $this->getRequest()->getParam('id');
        $method    = Mage::getModel('ultimate_shipper/method');
        if ($methodId) {
            $method->load($methodId);
        }
        Mage::register('current_method', $method);
        return $method;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('ultimate_shipper')->__('Ultimate Shipper'))
             ->_title(Mage::helper('ultimate_shipper')->__('Methods'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit method - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function editAction()
    {
        $methodId    = $this->getRequest()->getParam('id');
        $method      = $this->_initMethod();
        if ($methodId && !$method->getId()) {
            $this->_getSession()->addError(
                Mage::helper('ultimate_shipper')->__('This method no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getMethodData(true);
        if (!empty($data)) {
            $method->setData($data);
        }
        Mage::register('method_data', $method);
        $this->loadLayout();
        $this->_title(Mage::helper('ultimate_shipper')->__('Ultimate Shipper'))
             ->_title(Mage::helper('ultimate_shipper')->__('Methods'));
        if ($method->getId()) {
            $this->_title($method->getMethodName());
        } else {
            $this->_title(Mage::helper('ultimate_shipper')->__('Add method'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new method action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save method - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('method')) {
            try {
                $method = $this->_initMethod();
                $method->addData($data);
                $method->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ultimate_shipper')->__('Method was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $method->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setMethodData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('ultimate_shipper')->__('There was a problem saving the method.')
                );
                Mage::getSingleton('adminhtml/session')->setMethodData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('ultimate_shipper')->__('Unable to find method to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete method - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $method = Mage::getModel('ultimate_shipper/method');
                $method->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ultimate_shipper')->__('Method was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('ultimate_shipper')->__('There was an error deleting method.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('ultimate_shipper')->__('Could not find method to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete method - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function massDeleteAction()
    {
        $methodIds = $this->getRequest()->getParam('method');
        if (!is_array($methodIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('ultimate_shipper')->__('Please select methods to delete.')
            );
        } else {
            try {
                foreach ($methodIds as $methodId) {
                    $method = Mage::getModel('ultimate_shipper/method');
                    $method->setId($methodId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ultimate_shipper')->__('Total of %d methods were successfully deleted.', count($methodIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('ultimate_shipper')->__('There was an error deleting methods.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function massStatusAction()
    {
        $methodIds = $this->getRequest()->getParam('method');
        if (!is_array($methodIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('ultimate_shipper')->__('Please select methods.')
            );
        } else {
            try {
                foreach ($methodIds as $methodId) {
                $method = Mage::getSingleton('ultimate_shipper/method')->load($methodId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d methods were successfully updated.', count($methodIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('ultimate_shipper')->__('There was an error updating methods.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function exportCsvAction()
    {
        $fileName   = 'method.csv';
        $content    = $this->getLayout()->createBlock('ultimate_shipper/adminhtml_method_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function exportExcelAction()
    {
        $fileName   = 'method.xls';
        $content    = $this->getLayout()->createBlock('ultimate_shipper/adminhtml_method_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author RSMD Partners
     */
    public function exportXmlAction()
    {
        $fileName   = 'method.xml';
        $content    = $this->getLayout()->createBlock('ultimate_shipper/adminhtml_method_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author RSMD Partners
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('ultimate_shipper/method');
    }
}
