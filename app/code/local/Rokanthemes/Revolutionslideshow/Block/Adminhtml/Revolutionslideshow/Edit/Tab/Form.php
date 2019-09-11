<?php


class Rokanthemes_Revolutionslideshow_Block_Adminhtml_Revolutionslideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{

		$data = array();
		if (Mage::getSingleton('adminhtml/session')->getRevolutionslideshowData()) {
			$data = Mage::getSingleton('adminhtml/session')->getRevolutionslideshowData();
		} elseif (Mage::registry('revolutionslideshow_data')) {
			$data = Mage::registry('revolutionslideshow_data')->getData();
		}

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('revolutionslideshow_form', array('legend' => Mage::helper('revolutionslideshow')->__('Athlete Slide information')));
		$fieldset->addType('colorpicker', 'Rokanthemes_Revolutionslideshow_Block_Adminhtml_Revolutionslideshow_Helper_Form_Colorpicker');

		$fieldset->addField('store_id', 'multiselect', array(
			'name' => 'stores[]',
			'label' => Mage::helper('revolutionslideshow')->__('Store View'),
			'title' => Mage::helper('revolutionslideshow')->__('Store View'),
			'required' => true,
			'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
		));

		$fieldset->addField('image', 'image', array(
			'label' => Mage::helper('revolutionslideshow')->__('Background Image'),
			'required' => false,
			'name' => 'image',
		));

		$fieldset->addField('title_color', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Title color'),
			'name' => 'title_color',
			'note' => 'Leave empty to use default colors',
		));
		$fieldset->addField('title_bg', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Title background'),
			'name' => 'title_bg',
			'note' => 'Leave empty to use default colors',
		));

		$fieldset->addField('title', 'textarea', array(
			'label' => Mage::helper('revolutionslideshow')->__('Title'),
			'required' => false,
			'name' => 'title',
		));

		$fieldset->addField('link_color', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Link color'),
			'name' => 'link_color',
			'note' => 'Leave empty to use default colors',
		));
		$fieldset->addField('link_bg', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Link background'),
			'name' => 'link_bg',
			'note' => 'Leave empty to use default colors',
		));
		$fieldset->addField('link_hover_color', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Link hover color'),
			'name' => 'link_hover_color',
			'note' => 'Leave empty to use default colors',
		));
		$fieldset->addField('link_hover_bg', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Link hover background'),
			'name' => 'link_hover_bg',
			'note' => 'Leave empty to use default colors',
		));

		$fieldset->addField('link_text', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Link text'),
			'required' => false,
			'name' => 'link_text',
		));
		$fieldset->addField('link_href', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Link Url'),
			'required' => false,
			'name' => 'link_href',
		));

		$fieldset->addField('banner_1_img', 'image', array(
			'label' => Mage::helper('revolutionslideshow')->__('Banner 1 image'),
			'required' => false,
			'name' => 'banner_1_img',
		));
		$fieldset->addField('banner_1_imgX2', 'image', array(
			'label' => Mage::helper('athlete')->__('Banner 1 image for Retina'),
			'required' => false,
			'name' => 'banner_1_imgX2',
			'note' => 'Upload double size image for retina<br/>',
		));
		$fieldset->addField('banner_1_href', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Banner 1 Url'),
			'required' => false,
			'name' => 'banner_1_href',
		));

		$fieldset->addField('banner_2_img', 'image', array(
			'label' => Mage::helper('revolutionslideshow')->__('Banner 2 image'),
			'required' => false,
			'name' => 'banner_2_img',
		));
		$fieldset->addField('banner_2_imgX2', 'image', array(
			'label' => Mage::helper('athlete')->__('Banner 2 image for Retina'),
			'required' => false,
			'name' => 'banner_2_imgX2',
			'note' => 'Upload double size image for retina<br/>' ,
		));
		$fieldset->addField('banner_2_href', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Banner 2 Url'),
			'required' => false,
			'name' => 'banner_2_href',
		));

		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('revolutionslideshow')->__('Status'),
			'name' => 'status',
			'values' => array(
				array(
					'value' => 1,
					'label' => Mage::helper('revolutionslideshow')->__('Enabled'),
				),
				array(
					'value' => 2,
					'label' => Mage::helper('revolutionslideshow')->__('Disabled'),
				),
			),
		));

		$fieldset->addField('sort_order', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Sort Order'),
			'required' => false,
			'name' => 'sort_order',
		));

		if (Mage::getSingleton('adminhtml/session')->getRevolutionslideshowData()) {
			$form->setValues(Mage::getSingleton('adminhtml/session')->getRevolutionslideshowData());
			Mage::getSingleton('adminhtml/session')->getRevolutionslideshowData(null);
		} elseif (Mage::registry('revolutionslideshow_data')) {
			$form->setValues(Mage::registry('revolutionslideshow_data')->getData());
		}
		return parent::_prepareForm();
	}
}