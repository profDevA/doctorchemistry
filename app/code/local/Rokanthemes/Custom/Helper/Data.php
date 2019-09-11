<?php


class Rokanthemes_Custom_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Retrieve config value for store by path
	 *
	 * @param string $path
	 * @param string $section
	 * @param int $store
	 * @return mixed
	 */
	public function getCfg($path, $section = 'custom', $store = NULL)
	{
		if ($store == NULL) {
			$store = Mage::app()->getStore()->getId();
		}
		if (empty($path)) {
			$path = $section;
		} else {
			$path = $section . '/' . $path;
		}
		return Mage::getStoreConfig($path, $store);
	}

	/**
	 * Shortcut to getCfg function for appearance section
	 *
	 * @param $path
	 * @return mixed
	 */
	public function getAppearanceCfg($path)
	{
		return $this->getCfg($path, 'custom_appearance');
	}

	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return string - label html code
	 */
	public function getLabel(Mage_Catalog_Model_Product $product, $_prefix = '')
	{
		if (!($product instanceof Mage_Catalog_Model_Product))
			return;

		$html = '';
		$config = $this->getCfg('labels');
		if (!$config["new_label"] && !$config["sale_label"]) {
			return $html;
		}

		if ($config["new_label"] && $this->isNew($product)) {
			$html .= '<div class="new-label label-' . $config[$_prefix."new_label_position"] . '">'.$config['new_label_text'].'</div>';
		}
		if ($config["sale_label"] && $this->isOnSale($product)) {
			$html .= '<div class="sale-label label-' . $config[$_prefix."sale_label_position"] . '">'.$config['sale_label_text'].'</div>';
		}

		return $html;
	}

	protected function _checkDate($from, $to)
	{
		$today = strtotime(
			Mage::app()->getLocale()->date()
				->setTime('00:00:00')
				->toString(Varien_Date::DATETIME_INTERNAL_FORMAT)
		);

		if ($from && $today < $from) {
			return false;
		}
		if ($to && $today > $to) {
			return false;
		}
		if (!$to && !$from) {
			return false;
		}
		return true;
	}

	public function isNew($product)
	{
		$from = strtotime($product->getData('news_from_date'));
		$to = strtotime($product->getData('news_to_date'));

		return $this->_checkDate($from, $to);
	}

	public function isOnSale($_product)
	{
		$_taxHelper  = Mage::helper('tax');
		if (!$_product->isGrouped()) {
			$_price = $_taxHelper->getPrice($_product, $_product->getPrice());
			$_finalPrice = $_taxHelper->getPrice($_product, $_product->getFinalPrice());
			if ($_finalPrice < $_price){
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns the identifier for the currently rendered CMS page.
	 * If current page is not from CMS, null is returned.
	 * @return String | Null
	 */
	public function getCurrentCmsPage()
	{
		return Mage::getSingleton('cms/page')->getIdentifier();
	}

	public function getThemeCss()
	{
		$websiteCode = Mage::app()->getWebsite()->getCode();
		$storeCode = Mage::app()->getStore()->getCode();
		$cssFile = "css/options_{$websiteCode}_{$storeCode}.css";

		return $cssFile;
	}

}