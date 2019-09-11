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
 * Allowed Methods Comment model
 *
 * @category    Ultimate
 * @package     Ultimate_Shipper
 * @author      RSMD Partners
 */
class Ultimate_Shipper_Model_Adminhtml_Allowedmethods_Comment
{
    public function getCommentText()
    {
        $label = Mage::helper('ultimate_shipper')->__('Add Custom Method');
        $url = Mage::helper('adminhtml')->getUrl('adminhtml/shipper_method');
        $comment = "Select the custom methods you defined earlier or you can add custom method now. 
        			Do not select the method that you selected in Free Methods. Else it will show 
        			multiple times. ";
        return $comment.'<a href="'.$url.'" target=_blank >'.$label.'</a>';
    }
}