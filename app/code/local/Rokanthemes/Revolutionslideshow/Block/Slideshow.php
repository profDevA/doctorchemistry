<?php

class Rokanthemes_Revolutionslideshow_Block_Slideshow extends Mage_Core_Block_Template
{
	protected function _beforeToHtml()
	{
		$config = Mage::getStoreConfig('revolutionslideshow', Mage::app()->getStore()->getId());
		if (Mage::helper('revolutionslideshow')->isSlideshowEnabled()) {
			$this->setTemplate('rokanthemes/slideshow/revolution.phtml');
		}

		return $this;
	}

	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

	public function getSlideshow()
	{
		if (!$this->hasData('revolutionslideshow')) {
			$this->setData('revolutionslideshow', Mage::registry('revolutionslideshow'));
		}
		return $this->getData('revolutionslideshow');

	}

	public function getSlides()
	{
		$model = Mage::getModel('revolutionslideshow/rokanthemerevolution');
		$slides = $model->getCollection()
			->addStoreFilter(Mage::app()->getStore())
			->addFieldToSelect('*')
			->addFieldToFilter('status', 1)
			->setOrder('sort_order', 'asc');
		return $slides;
	}

}