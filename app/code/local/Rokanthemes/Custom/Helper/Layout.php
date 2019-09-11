<?php


class Rokanthemes_Custom_Helper_Layout extends Mage_Core_Helper_Abstract
{
	public function getMaxWidth($store = null)
	{
		$width = Mage::helper('custom')->getCfg('layout/max_width', 'custom', $store);
		if ($width == 'custom') {
			return Mage::helper('custom')->getCfg('layout/custom_width', 'custom', $store);
		}
		return $width;
	}

	public function getCustomWidth($store = null)
	{
		$width = Mage::helper('custom')->getCfg('layout/max_width', 'custom', $store);
		if ($width == 'custom') {
			return Mage::helper('custom')->getCfg('layout/custom_width', 'custom', $store);
		}
		return 0;
	}

	public function getMaxBreakpoint($width)
	{
		$breakpoints = $this->getBreakpoints();
		foreach ($breakpoints as $_bp ) {
			if ( $width <= $_bp ) {
				return $_bp;
			}
		}
		return $_bp;
	}


	public function getBreakpoints()
	{
		return array(
			480,
			768,
			1024,
			1280,
			1360,
			1440,
			1680,
			9999,
		);
	}

	public function getBreakpointsContentWidth( $onlyContent = false)
	{
		$width = array(
			'480' => 426,
			'768' => 756,
			'1024' => 960,
			'1280' => 1200,
			'1360' => 1300,
			'1440' => 1380,
			'1680' => 1520,
		);
		if ($onlyContent) {
			return array_values($width);
		} else {
			return $width;
		}
	}

	public function getSliderItems($columns)
	{
		$itemsCustom = '';
		switch ( $columns ) {
			case 2:
				$itemsCustom = '[ [0, 2] ]';
				break;
			case 3:
				$itemsCustom = '[ [0, 2], [426, 3] ]';
				break;
			case 4:
				$itemsCustom = '[ [0, 2], [426, 3], [756, 4] ]';
				break;
			case 5:
				$itemsCustom = '[ [0, 2], [426, 3], [756, 4], [960, 5]]';
				break;
			case 6:
				$itemsCustom = '[ [0, 2], [426, 3], [756, 5], [960, 6] ]';
				break;
			case 7:
				$itemsCustom = '[ [0, 2], [426, 3], [756, 5], [960, 7] ]';
				break;
			default:
				$itemsCustom = '[ [0, 2], [426, 3], [756, 4] ]';
				break;
		}
		return $itemsCustom;
	}

	public function getBrandsSliderItems()
	{
		$brand_width = Mage::helper('custom')->getCfg('main/image_width', 'custom_brands');
		if ( !is_numeric($brand_width) || $brand_width < 0 || $brand_width > 300 ) {
			$brand_width = 96;
		}

		$itemsCustom = array();
		$itemsCustom[] = "[0, ". ceil(300 / $brand_width)."]";
		$contentBreakpoints = $this->getBreakpointsContentWidth(true);
		foreach ( $contentBreakpoints as $_cb ) {
			$itemsCustom[] = "[$_cb, ".ceil($_cb / $brand_width)."]";
		}

		return '[ '.implode(',', $itemsCustom).' ]';
	}

	public function getBannerSliderItems( $width )
	{
		$itemsCustom = array();
		$itemsCustom[] = "[0, 1]";
		$j = 2;
		for ( $i=$width+1; $i < 3000; $i+=$width ) {
			$itemsCustom[] = "[$i, $j]";
			$j++;
		}

		return '[ '.implode(',', $itemsCustom).' ]';
	}



}