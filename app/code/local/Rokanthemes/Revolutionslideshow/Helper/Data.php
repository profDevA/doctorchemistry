<?php


class Rokanthemes_Revolutionslideshow_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isSlideshowEnabled()
	{
		$config = Mage::getStoreConfig('revolutionslideshow', Mage::app()->getStore()->getId());
		$request = Mage::app()->getFrontController()->getRequest();
		$route = Mage::app()->getFrontController()->getRequest()->getRouteName();
		$action = Mage::app()->getFrontController()->getRequest()->getActionName();
		$show = false;
		if ($config['config']['enabled']) {
			$show = true;
			if ($config['config']['show'] == 'home') {
				$show = false;
				if ($request->getModuleName() == 'cms' && $request->getControllerName() == 'index' && $request->getActionName() == 'index') {
					$show = true;
				}
			}
			if ($show && ($route == 'customer' && ($action == 'login' || $action == 'forgotpassword' || $action == 'create'))) {
				$show = false;
			}
		}
		return $show;
	}

	public function getSupportedColors()
	{
		return array(
			array(
				'value'     => 'white',
				'label'     => Mage::helper('revolutionslideshow')->__('white'),
			),
			array(
				'value'     => 'black',
				'label'     => Mage::helper('revolutionslideshow')->__('black'),
			),
			array(
				'value'     => 'red',
				'label'     => Mage::helper('revolutionslideshow')->__('red'),
			),
			array(
				'value'     => 'green',
				'label'     => Mage::helper('revolutionslideshow')->__('green'),
			),
			array(
				'value'     => 'blue',
				'label'     => Mage::helper('revolutionslideshow')->__('blue'),
			),
			array(
				'value'     => 'yellow',
				'label'     => Mage::helper('revolutionslideshow')->__('yellow'),
			),
		);
	}
}