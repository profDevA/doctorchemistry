<?php
/**
 * Webshopapps Shipping Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * Shipping MatrixRates
 *
 * @category   Webshopapps
 * @package    Webshopapps_Matrixrate
 * @copyright  Copyright (c) 2010 Zowta Ltd (http://www.webshopapps.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Karen Baker <sales@webshopapps.com>
*/
class Webshopapps_Matrixrate_Model_Mysql4_Carrier_Matrixrate extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('shipping/matrixrate', 'pk');
    }

    public function getNewRate(Mage_Shipping_Model_Rate_Request $request,$zipRangeSet=0)
    {
        $read = $this->_getReadAdapter();
        $write = $this->_getWriteAdapter();

		$postcode = $request->getDestPostcode();
        $table = Mage::getSingleton('core/resource')->getTableName('matrixrate_shipping/matrixrate');

		if ($zipRangeSet && is_numeric($postcode)) {
			#  Want to search for postcodes within a range
			$zipSearchString = ' AND '.$postcode.' BETWEEN dest_zip AND dest_zip_to )';		
		} else {
			$zipSearchString = $read->quoteInto(" AND ? LIKE dest_zip )", $postcode);
		}

		for ($j=0;$j<10;$j++)
		{

			$select = $read->select()->from($table);
			switch($j) {
				case 0:
					$select->where(
						$read->quoteInto(" (dest_country_id=? ", $request->getDestCountryId()).
							$read->quoteInto(" AND dest_region_id=? ", $request->getDestRegionId()).
							$read->quoteInto(" AND STRCMP(LOWER(dest_city),LOWER(?)) = 0  ", $request->getDestCity()).
							$zipSearchString
						);
					break;
				case 1:
					$select->where(
						$read->quoteInto(" (dest_country_id=? ", $request->getDestCountryId()).
							$read->quoteInto(" AND dest_region_id=?  AND dest_city=''", $request->getDestRegionId()).
							$zipSearchString
						);
					break;
				case 2:
					$select->where(
						$read->quoteInto(" (dest_country_id=? ", $request->getDestCountryId()).
							$read->quoteInto(" AND dest_region_id=? ", $request->getDestRegionId()).
							$read->quoteInto(" AND STRCMP(LOWER(dest_city),LOWER(?)) = 0  AND dest_zip='')", $request->getDestCity())
						);
					break;
				case 3:
					$select->where(
					   $read->quoteInto("  (dest_country_id=? ", $request->getDestCountryId()).
							$read->quoteInto(" AND STRCMP(LOWER(dest_city),LOWER(?)) = 0  AND dest_region_id='0'", $request->getDestCity()).
							$zipSearchString
					   );
					break;
				case 4:
					$select->where(
					   $read->quoteInto("  (dest_country_id=? ", $request->getDestCountryId()).
							$read->quoteInto(" AND STRCMP(LOWER(dest_city),LOWER(?)) = 0  AND dest_region_id='0' AND dest_zip='') ", $request->getDestCity())
					   );
					break;
				case 5:
					$select->where(
						$read->quoteInto("  (dest_country_id=? AND dest_region_id='0' AND dest_city='' ", $request->getDestCountryId()).
							$zipSearchString
						);
					break;
				case 6:
					$select->where(
					   $read->quoteInto("  (dest_country_id=? ", $request->getDestCountryId()).
							$read->quoteInto(" AND dest_region_id=? AND dest_city='' AND dest_zip='') ", $request->getDestRegionId())
					   );
					break;

				case 7:
					$select->where(
					   $read->quoteInto("  (dest_country_id=? AND dest_region_id='0' AND dest_city='' AND dest_zip='') ", $request->getDestCountryId())
					);
					break;
				case 8:
						$select->where(
						   "  (dest_country_id='0' AND dest_region_id='0'".
						   $zipSearchString
						);
					break;

				case 9:
					$select->where(
							"  (dest_country_id='0' AND dest_region_id='0' AND dest_zip='')"
				);
					break;
			}


			if (is_array($request->getMRConditionName())) {
				$i = 0;
				foreach ($request->getMRConditionName() as $conditionName) {
					if ($i == 0) {
						$select->where('condition_name=?', $conditionName);
					} else {
						$select->orWhere('condition_name=?', $conditionName);
					}
					$select->where('condition_from_value<=?', $request->getData($conditionName));


					$i++;
				}
			} else {
				$select->where('condition_name=?', $request->getMRConditionName());
				$select->where('condition_from_value<=?', $request->getData($request->getMRConditionName()));
				$select->where('condition_to_value>=?', $request->getData($request->getMRConditionName()));
			}

			$select->where('website_id=?', $request->getWebsiteId());

			$select->order('dest_country_id DESC');
			$select->order('dest_region_id DESC');
			$select->order('dest_zip DESC');
			$select->order('condition_from_value DESC');
			/*
			pdo has an issue. we cannot use bind
			*/

			$newdata=array();
			$row = $read->fetchAll($select);
			if (!empty($row))
			{
				// have found a result or found nothing and at end of list!
				foreach ($row as $data) {
					$newdata[]=$data;
				}
				break;
			}
		}
		return $newdata;

    }

    public function uploadAndImport(Varien_Object $object)
    {
        $csvFile = $_FILES["groups"]["tmp_name"]["matrixrate"]["fields"]["import"]["value"];

        if (!empty($csvFile)) {

            $csv = trim(file_get_contents($csvFile));

            $table = Mage::getSingleton('core/resource')->getTableName('matrixrate_shipping/matrixrate');

            $websiteId = $object->getScopeId();
            $websiteModel = Mage::app()->getWebsite($websiteId);
            /*
            getting condition name from post instead of the following commented logic
            */

            if (isset($_POST['groups']['matrixrate']['fields']['condition_name']['inherit'])) {
                $conditionName = (string)Mage::getConfig()->getNode('default/carriers/matrixrate/condition_name');
            } else {
                $conditionName = $_POST['groups']['matrixrate']['fields']['condition_name']['value'];
            }

//            $conditionName = $object->getValue();
//            if ($conditionName{0} == '_') {
//                $conditionName = Mage::helper('core/string')->substr($conditionName, 1, strpos($conditionName, '/')-1);
//            } else {
//                $conditionName = $websiteModel->getConfig('carriers/matrixrate/condition_name');
//            }
            $conditionFullName = Mage::getModel('matrixrate_shipping/carrier_matrixrate')->getCode('condition_name_short', $conditionName);
            if (!empty($csv)) {
                $exceptions = array();
                $csvLines = explode("\n", $csv);
                $csvLine = array_shift($csvLines);
                $csvLine = $this->_getCsvValues($csvLine);
                if (count($csvLine) < 7) {
                    $exceptions[0] = Mage::helper('shipping')->__('Invalid Matrix Rates File Format');
                }

                $countryCodes = array();
                $regionCodes = array();
                foreach ($csvLines as $k=>$csvLine) {
                    $csvLine = $this->_getCsvValues($csvLine);
                    if (count($csvLine) > 0 && count($csvLine) < 7) {
                        $exceptions[0] = Mage::helper('shipping')->__('Invalid Matrix Rates File Format');
                    } else {
                        $countryCodes[] = $csvLine[0];
                        $regionCodes[] = $csvLine[1];
                    }
                }

                if (empty($exceptions)) {
                    $data = array();
                    $countryCodesToIds = array();
                    $regionCodesToIds = array();
                    $countryCodesIso2 = array();

                    $countryCollection = Mage::getResourceModel('directory/country_collection')->addCountryCodeFilter($countryCodes)->load();
                    foreach ($countryCollection->getItems() as $country) {
                        $countryCodesToIds[$country->getData('iso3_code')] = $country->getData('country_id');
                        $countryCodesToIds[$country->getData('iso2_code')] = $country->getData('country_id');
                        $countryCodesIso2[] = $country->getData('iso2_code');
                    }

     				$regionCollection = Mage::getResourceModel('directory/region_collection')
                        ->addRegionCodeFilter($regionCodes)
                        ->addCountryFilter($countryCodesIso2)
                        ->load();

                    foreach ($regionCollection->getItems() as $region) {
                        $regionCodesToIds[$countryCodesToIds[$region->getData('country_id')]][$region->getData('code')] = $region->getData('region_id');
                    }

                    foreach ($csvLines as $k=>$csvLine) {

                        $csvLine = $this->_getCsvValues($csvLine);

                        if (empty($countryCodesToIds) || !array_key_exists($csvLine[0], $countryCodesToIds)) {
                            $countryId = '0';
                            if ($csvLine[0] != '*' && $csvLine[0] != '') {
                                $exceptions[] = Mage::helper('shipping')->__('Invalid Country "%s" in the Row #%s', $csvLine[0], ($k+1));
                            }
                        } else {
                            $countryId = $countryCodesToIds[$csvLine[0]];
                        }

                        if (!isset($countryCodesToIds[$csvLine[0]])
                            || !isset($regionCodesToIds[$countryCodesToIds[$csvLine[0]]])
                            || !array_key_exists($csvLine[1], $regionCodesToIds[$countryCodesToIds[$csvLine[0]]])) {
                            $regionId = '0';
                            if ($csvLine[1] != '*' && $csvLine[1] != '') {
                                $exceptions[] = Mage::helper('shipping')->__('Invalid Region/State "%s" in the Row #%s', $csvLine[1], ($k+1));
                            }
                        } else {
                            $regionId = $regionCodesToIds[$countryCodesToIds[$csvLine[0]]][$csvLine[1]];
                        }

						if (count($csvLine)==9) {
							// we are searching for postcodes in ranges & including cities
							if ($csvLine[2] == '*' || $csvLine[2] == '') {
								$city = '';
							} else {
								$city = $csvLine[2];
							}


							if ($csvLine[3] == '*' || $csvLine[3] == '') {
								$zip = '';
							} else {
								$zip = $csvLine[3];
							}


							if ($csvLine[4] == '*' || $csvLine[4] == '') {
								$zip_to = '';
							} else {
								$zip_to = $csvLine[4];
							}


							if (!$this->_isPositiveDecimalNumber($csvLine[5]) || $csvLine[5] == '*' || $csvLine[5] == '') {
								$exceptions[] = Mage::helper('shipping')->__('Invalid %s From "%s" in the Row #%s', $conditionFullName, $csvLine[5], ($k+1));
							} else {
								$csvLine[5] = (float)$csvLine[5];
							}

							if (!$this->_isPositiveDecimalNumber($csvLine[6])) {
								$exceptions[] = Mage::helper('shipping')->__('Invalid %s To "%s" in the Row #%s', $conditionFullName, $csvLine[6], ($k+1));
							} else {
								$csvLine[6] = (float)$csvLine[6];
							}


							$data[] = array('website_id'=>$websiteId, 'dest_country_id'=>$countryId, 'dest_region_id'=>$regionId, 'dest_city'=>$city, 'dest_zip'=>$zip, 'dest_zip_to'=>$zip_to, 'condition_name'=>$conditionName, 'condition_from_value'=>$csvLine[5],'condition_to_value'=>$csvLine[6], 'price'=>$csvLine[7], 'delivery_type'=>$csvLine[8]);

						}
						else {

							if ($csvLine[2] == '*' || $csvLine[2] == '') {
								$zip = '';
							} else {
								$zip = $csvLine[2]."%";
							}

							$city='';
							$zip_to = '';

							if (!$this->_isPositiveDecimalNumber($csvLine[3]) || $csvLine[3] == '*' || $csvLine[3] == '') {
								$exceptions[] = Mage::helper('shipping')->__('Invalid %s From "%s" in the Row #%s', $conditionFullName, $csvLine[3], ($k+1));
							} else {
								$csvLine[3] = (float)$csvLine[3];
							}

							if (!$this->_isPositiveDecimalNumber($csvLine[4])) {
								$exceptions[] = Mage::helper('shipping')->__('Invalid %s To "%s" in the Row #%s', $conditionFullName, $csvLine[4], ($k+1));
							} else {
								$csvLine[4] = (float)$csvLine[4];
							}
							$data[] = array('website_id'=>$websiteId, 'dest_country_id'=>$countryId, 'dest_region_id'=>$regionId,  'dest_city'=>$city,'dest_zip'=>$zip,'dest_zip_to'=>$zip_to,  'condition_name'=>$conditionName, 'condition_from_value'=>$csvLine[3],'condition_to_value'=>$csvLine[4], 'price'=>$csvLine[5], 'delivery_type'=>$csvLine[6]);
						}


						$dataDetails[] = array('country'=>$csvLine[0], 'region'=>$csvLine[1]);

                    }
                }
                if (empty($exceptions)) {
                    $connection = $this->_getWriteAdapter();


                    $condition = array(
                        $connection->quoteInto('website_id = ?', $websiteId),
                        $connection->quoteInto('condition_name = ?', $conditionName),
                    );
                    $connection->delete($table, $condition);

                    foreach($data as $k=>$dataLine) {
                        try {
                           $connection->insert($table, $dataLine);
                        } catch (Exception $e) {
                            $exceptions[] = Mage::helper('shipping')->__('Duplicate Row #%s (Country "%s", Region/State "%s", City "%s", Zip From "%s", Zip To "%s", Delivery Type "%s", Value From "%s" and Value To "%s")', ($k+1), $dataDetails[$k]['country'], $dataDetails[$k]['region'], $dataLine['dest_city'], $dataLine['dest_zip'],  $dataLine['dest_zip_to'], $dataLine['delivery_type'], $dataLine['condition_from_value'], $dataLine['condition_to_value']);
                        }
                    }
                }
                if (!empty($exceptions)) {
                    throw new Exception( "\n" . implode("\n", $exceptions) );
                }
            }
        }
    }

    private function _getCsvValues($string, $separator=",")
    {
        $elements = explode($separator, trim($string));
        for ($i = 0; $i < count($elements); $i++) {
            $nquotes = substr_count($elements[$i], '"');
            if ($nquotes %2 == 1) {
                for ($j = $i+1; $j < count($elements); $j++) {
                    if (substr_count($elements[$j], '"') > 0) {
                        // Put the quoted string's pieces back together again
                        array_splice($elements, $i, $j-$i+1, implode($separator, array_slice($elements, $i, $j-$i+1)));
                        break;
                    }
                }
            }
            if ($nquotes > 0) {
                // Remove first and last quotes, then merge pairs of quotes
                $qstr =& $elements[$i];
                $qstr = substr_replace($qstr, '', strpos($qstr, '"'), 1);
                $qstr = substr_replace($qstr, '', strrpos($qstr, '"'), 1);
                $qstr = str_replace('""', '"', $qstr);
            }
            $elements[$i] = trim($elements[$i]);
        }
        return $elements;
    }

    private function _isPositiveDecimalNumber($n)
    {
        return preg_match ("/^[0-9]+(\.[0-9]*)?$/", $n);
    }

}
