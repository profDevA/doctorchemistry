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

class Mediarocks_SocialMetaTags_Model_Observer 
{

    /**
     * Add social meta attributes to new fieldset
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function prepareForm(Varien_Event_Observer $observer)
    {
		// hide formfields in backend if module output is disabled (doesn't work!)
		//$class = !Mage::helper('core')->isModuleEnabled('Mediarocks_SocialMetaTags') ? ' hidden' : '';
		
        $form = $observer->getEvent()->getForm();
		$fieldset = $form->addFieldset(
            'social_meta_information',
            array(
                 'legend' => 'Social Meta Information',
                 'class' => 'fieldset-wide'
            )
        );

        $fieldset->addField('fb_share_image', 'image', array(
            'name' => 'fb_share_image',
            'label' => 'Facebook Share Image',
            'title' => 'Facebook Share Image'
        ));

        $fieldset->addField('facebook_meta_title', 'text', array(
            'name' => 'facebook_meta_title',
            'label' => 'Facebook Meta Title',
            'title' => 'Facebook Meta Title',
        ));

        $fieldset->addField('facebook_meta_description', 'textarea', array(
            'name' => 'facebook_meta_description',
            'label' => 'Facebook Meta Description',
            'title' => 'Facebook Meta Description',
        ));

        $fieldset->addField('twitter_share_image', 'image', array(
            'name' => 'twitter_share_image',
            'label' => 'Twitter Card Image',
           	'title' => 'Twitter Card Image'
        ));

        $fieldset->addField('twitter_meta_title', 'text', array(
            'name' => 'twitter_meta_title',
            'label' => 'Twitter Meta Title',
            'title' => 'Twitter Meta Title',
        ));

        $fieldset->addField('twitter_meta_description', 'textarea', array(
            'name' => 'twitter_meta_description',
            'label' => 'Twitter Meta Description',
            'title' => 'Twitter Meta Description',
        ));
    }

    /**
     * Save social share images
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function savePage(Varien_Event_Observer $observer)
    {
		// don't save module fields if module output is disabled
		//if (!Mage::helper('core')->isModuleEnabled('Mediarocks_SocialMetaTags'))
		//	return;
            
        $model = $observer->getEvent()->getPage();
        $request = $observer->getEvent()->getRequest();		
			
		if (is_array($_FILES['fb_share_image'])) {

    		if (isset($_FILES['fb_share_image']['name']) && $_FILES['fb_share_image']['name'] != '') {
    			$uploader = new Varien_File_Uploader('fb_share_image');
    
    			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
    			$uploader->setAllowRenameFiles(false);
    			$uploader->setFilesDispersion(false);
    			
    			$media_path  = Mage::getBaseDir('media') . DS . 'fb_share_image' . DS;
    			$file_name = 'cms_';
    			$uploader->save($media_path, $file_name . $_FILES['fb_share_image']['name']);
    
    			$data['fb_share_image'] = 'fb_share_image' . DS . $file_name . $_FILES['fb_share_image']['name'];
    			$data['fb_share_image'] = $data['fb_share_image'];
    			$model->setFbShareImage($data['fb_share_image']);
    		} else {
    			$data = $request->getPost();
    			if(isset($data['fb_share_image']['delete']) && $data['fb_share_image']['delete'] == 1) {
    				$model->setFbShareImage('');
    			} else {
                    $data = $model->getData();
                    if (isset($data['fb_share_image'])) {
                        $model->setFbShareImage(implode($data['fb_share_image']));
                    }
                }
    		}
    	}
            
        if (is_array($_FILES['twitter_share_image'])) {

            if (isset($_FILES['twitter_share_image']['name']) && $_FILES['twitter_share_image']['name'] != '') {
                $uploader = new Varien_File_Uploader('twitter_share_image');
    
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                
                $media_path  = Mage::getBaseDir('media') . DS . 'twitter_share_image' . DS;
                $file_name = 'cms_';
                $uploader->save($media_path, $file_name . $_FILES['twitter_share_image']['name']);
    
                $data['twitter_share_image'] = 'twitter_share_image' . DS . $file_name . $_FILES['twitter_share_image']['name'];
                $data['twitter_share_image'] = $data['twitter_share_image'];
                $model->setTwitterShareImage($data['twitter_share_image']);
            } else {
                $data = $request->getPost();
                if(isset($data['twitter_share_image']['delete']) && $data['twitter_share_image']['delete'] == 1) {
                    $model->setTwitterShareImage('');
                } else {
                    $data = $model->getData();
                    if (isset($data['twitter_share_image'])) {
                        $model->setTwitterShareImage(implode($data['twitter_share_image']));
                    }
                }
            }
        }
    }

    /**
     * Shortcut to getRequest
     *
     * @return Mage_Core_Controller_Request_Http
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}