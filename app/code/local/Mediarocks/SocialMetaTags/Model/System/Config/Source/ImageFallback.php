<?php
/**
 * Media Rocks GbR
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA that is bundled with 
 * this package in the file MEDIAROCKS-LICENSE-COMMUNITY.txt.
 * It is also available through the world-wide-web at this URL:
 * http://solutions.mediarocks.de/MEDIAROCKS-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package is designed for Magento COMMUNITY edition. 
 * Media Rocks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Media Rocks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please send an email to support@mediarocks.de
 *
 */

class Mediarocks_SocialMetaTags_Model_System_Config_Source_ImageFallback
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'base_image,small_image,thumbnail',
                'label' => 'base -> small -> thumbnail',
            ),
            array(
                'value' => 'base_image,thumbnail,small_image',
                'label' => 'base -> thumbnail -> small',
            ),
            array(
                'value' => 'small_image,base_image,thumbnail',
                'label' => 'small -> base -> thumbnail',
            ),
            array(
                'value' => 'small_image,thumbnail,base_image',
                'label' => 'small -> thumbnail -> base',
            ),
            array(
                'value' => 'thumbnail,small_image,base_image',
                'label' => 'thumbnail -> small -> base',
            ),
            array(
                'value' => 'thumbnail,base_image,small_image',
                'label' => 'thumbnail -> base -> small',
            ),
            array(
                'value' => 'custom',
                'label' => 'Custom Order',
            ),
        );
    }
}