<?php
/**  
 * @category    Ocodewire
 * @package     Ocodewire_OnePageCheckout
 * @author	Ocodewire
 */

$installer = $this;
$installer->startSetup();

$configObj = Mage::getConfig();
$isDhlint = (int)is_object($configObj->getNode('default/carriers/dhlint'));
$configParam = $configObj->getNode('default/carriers/dhlint/content_type');

if ($isDhlint && empty($configParam)) {
    $configObj->saveConfig('carriers/dhlint/content_type', "D", 'default', 'D');
    $configObj->cleanCache();
}

$installer->endSetup();
?>
