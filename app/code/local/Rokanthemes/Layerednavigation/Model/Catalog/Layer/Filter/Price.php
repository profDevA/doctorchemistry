<?php 
 class Rokanthemes_Layerednavigation_Model_Catalog_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Price
 {
 
   public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        /**
         * Filter must be string: $fromPrice-$toPrice
         */
        $filter = $request->getParam($this->getRequestVar());
		$fisrt = NULL;
        $last = NULL;
        if (isset($_GET['last'])) {
            $last = $_GET['last'];
        }
        if (isset($_GET['first'])) {
            $fisrt = $_GET['first'];
        }
        if(isset( $_GET['rate'])){
            $rate = $_GET['rate'];
            $last = $last / $rate;
            $fisrt = $fisrt / $rate;
        }
		if ($fisrt && $last) {
			$filter = $fisrt.'-'.$last;
		}
		Mage::log($filter, null,'layer.log');
        if (!$filter) {
            return $this;
        }

        //validate filter
        $filterParams = explode(',', $filter);
        $filter = $this->_validateFilter($filterParams[0]);
        if (!$filter) {
            return $this;
        }

        list($from, $to) = $filter;

        $this->setInterval(array($from, $to));

        $priorFilters = array();
        for ($i = 1; $i < count($filterParams); ++$i) {
            $priorFilter = $this->_validateFilter($filterParams[$i]);
            if ($priorFilter) {
                $priorFilters[] = $priorFilter;
            } else {
                //not valid data
                $priorFilters = array();
                break;
            }
        }
        if ($priorFilters) {
            $this->setPriorIntervals($priorFilters);
        }

        $this->_applyPriceRange();
        $this->getLayer()->getState()->addFilter($this->_createItem(
            $this->_renderRangeLabel(empty($from) ? 0 : $from, $to),
            $filter
        ));

        return $this;
    }
	
	 protected function _getItemsData()
    {
        if (Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_CALCULATION) == self::RANGE_CALCULATION_IMPROVED) {
           // return $this->_getCalculatedItemsData();
        } elseif ($this->getInterval()) {
          //  return array();
        }

        $range      = $this->getPriceRange();
        $dbRanges   = $this->getRangeItemCounts($range);
        $data       = array();

        if (!empty($dbRanges)) {
            $lastIndex = array_keys($dbRanges);
            $lastIndex = $lastIndex[count($lastIndex) - 1];

            foreach ($dbRanges as $index => $count) {
                $fromPrice = ($index == 1) ? '' : (($index - 1) * $range);
                $toPrice = ($index == $lastIndex) ? '' : ($index * $range);

                $data[] = array(
                    'label' => $this->_renderRangeLabel($fromPrice, $toPrice),
                    'value' => $fromPrice . '-' . $toPrice,
                    'count' => $count,
                );
            }
        }
        return $data;
    }
 
 }
