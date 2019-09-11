<?php

class SaveTheMage_CustomInvoiceAndOrderNumber_AdminControllersHere_OpsController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		
		$this->_addLeft($this->getLayout()->createBlock('SaveTheMage_CustomInvoiceAndOrderNumber_Block_ShowTabsAdminBlock'));
		
		$this->renderLayout();
                
	}
	
            public function postAction()
            {
		
		$post = $this->getRequest();
		
                try {
                        if (empty($post)) {
                            Mage::throwException($this->__('Invalid value.'));
                        }
            		
			$storeIds = $post->getParam('storeIds'); //Done
                        $OrderNumbers = $post->getParam('OrderNumbers'); //Done
                        $InvoiceNumbers = $post->getParam('InvoiceNumbers'); //Done
                              
                        $noNumber="";
                        foreach($OrderNumbers as $_on)
                        {
                            if( !empty( $_on ) ){
                                $noNumber = $_on;
                                break;
                            }
                        }
                        if( empty( $noNumber ) ){
                            foreach($InvoiceNumbers as $_in)
                            {
                                if( !empty( $_in ) ){
                                    $noNumber = $_in;
                                    break;
                                }
                            }
                        }
                        if( empty( $noNumber ) )
                        {
                            Mage::getSingleton('adminhtml/session')->addError("Order Number or Invoice Number is required.");
                        }
                        else
                        {
                            
                            $msg = "";

                            $model = Mage::getModel('savethemageCustomInvoiceAndOrderNumber/numbers');
                            if( empty( $model ) )
                            {
                                require_once ( Mage::getBaseDir('app') . '/code/local/SaveTheMage/CustomInvoiceAndOrderNumber/Model/numbers.php');
                                $model = new SaveTheMage_CustomInvoiceAndOrderNumber_Model_Numbers();
                            }
                            
                            $ONumber = "";
                            $INumber = "";
                            $i = 0;
                            $count = count($storeIds);
                            while( $i < $count )
                            {
                                if( !empty( $OrderNumbers[$i] ) )
                                {
                                    $model->saveNumber($storeIds[$i], $OrderNumbers[$i], 'order');
                                    $ONumber = $OrderNumbers[$i];
                                }
                                
                                if( !empty( $InvoiceNumbers[$i] ) )
                                {
                                    $model->saveNumber($storeIds[$i], $InvoiceNumbers[$i], 'invoice');
                                    $INumber = $InvoiceNumbers[$i];
                                }
                                
                                ++$i;
                            }
                            
                            if( !empty( $ONumber ) )
                            {
                                $msg = "Next Order Numbers changed successfully.";
                            }
                            
                                if( !empty( $msg ) && !empty( $INumber ) )
                                    $msg = "Next Invoice Numbers and " . $msg;
                                else if( empty( $ONumber ) )
                                    $msg = "Next Invoice Numbers changed successfully.";
                            
                            if( !empty( $msg ) )
                            {
                                $message = $this->__( $msg );
                                Mage::getSingleton('adminhtml/session')->addSuccess($message);
                            }
                            else
                            {
                                Mage::getSingleton('adminhtml/session')->addError("Order Number or Invoice Number is required. 2");
                            }
                        }
                        
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*');
	}
        
        
   public function getOrderInvoiceNumbersAction() { //UnUsed
       
       $model = Mage::getModel('savethemageCustomInvoiceAndOrderNumber/numbers');
       $output = array();
       $output['OrderNumber'] = $model->getOrderNumber( 1 );
       $output['InvoiceNumber'] = $model->getInvoiceNumber( 1 );
       
       $json = json_encode($output);
       
        $this->getResponse()
        ->clearHeaders()
        ->setHeader('Content-Type', 'application/json')
        ->setBody($json);
   }
}