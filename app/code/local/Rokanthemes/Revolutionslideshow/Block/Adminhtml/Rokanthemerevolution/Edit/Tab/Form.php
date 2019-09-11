<?php


class Rokanthemes_Revolutionslideshow_Block_Adminhtml_Rokanthemerevolution_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{

		$model = Mage::registry('revolutionslideshow_rokanthemerevolution');

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('revolutionslideshow_form', array('legend' => Mage::helper('revolutionslideshow')->__('Revolution Slide information')));
		$fieldset->addType('colorpicker', 'Rokanthemes_Revolutionslideshow_Block_Adminhtml_Rokanthemerevolution_Helper_Form_Colorpicker');

		$fieldset->addField('store_id', 'multiselect', array(
			'name' => 'stores[]',
			'label' => Mage::helper('revolutionslideshow')->__('Store View'),
			'title' => Mage::helper('revolutionslideshow')->__('Store View'),
			'required' => true,
			'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
		));

		$fieldset->addField('slide_bg', 'colorpicker', array(
			'label' => Mage::helper('revolutionslideshow')->__('Slide background'),
			'name' => 'slide_bg',
			'note' => 'Leave empty to use default colors',
		));

		$fieldset->addField('transition', 'select', array(
			'label' => Mage::helper('revolutionslideshow')->__('Transition'),
			'name' => 'transition',
			'values' => array(
				array(
					'value' => 'boxslide',
					'label' => Mage::helper('revolutionslideshow')->__('boxslide'),
				),
				array(
					'value' => 'boxfade',
					'label' => Mage::helper('revolutionslideshow')->__('boxfade'),
				),
				array(
					'value' => 'slotzoom-horizontal',
					'label' => Mage::helper('revolutionslideshow')->__('slotzoom-horizontal'),
				),
				array(
					'value' => 'slotslide-horizontal',
					'label' => Mage::helper('revolutionslideshow')->__('slotslide-horizontal'),
				),
				array(
					'value' => 'slotfade-horizontal',
					'label' => Mage::helper('revolutionslideshow')->__('slotfade-horizontal'),
				),
				array(
					'value' => 'slotzoom-vertical',
					'label' => Mage::helper('revolutionslideshow')->__('slotzoom-vertical'),
				),
				array(
					'value' => 'slotslide-vertical',
					'label' => Mage::helper('revolutionslideshow')->__('slotslide-vertical'),
				),
				array(
					'value' => 'slotfade-vertical',
					'label' => Mage::helper('revolutionslideshow')->__('slotfade-vertical'),
				),
				array(
					'value' => 'curtain-1',
					'label' => Mage::helper('revolutionslideshow')->__('curtain-1'),
				),
				array(
					'value' => 'curtain-2',
					'label' => Mage::helper('revolutionslideshow')->__('curtain-2'),
				),
				array(
					'value' => 'curtain-3',
					'label' => Mage::helper('revolutionslideshow')->__('curtain-3'),
				),
				array(
					'value' => 'slideleft',
					'label' => Mage::helper('revolutionslideshow')->__('slideleft'),
				),
				array(
					'value' => 'slideright',
					'label' => Mage::helper('revolutionslideshow')->__('slideright'),
				),
				array(
					'value' => 'slideup',
					'label' => Mage::helper('revolutionslideshow')->__('slideup'),
				),
				array(
					'value' => 'slidedown',
					'label' => Mage::helper('revolutionslideshow')->__('slidedown'),
				),
				array(
					'value' => 'fade',
					'label' => Mage::helper('revolutionslideshow')->__('fade'),
				),
				array(
					'value' => 'random',
					'label' => Mage::helper('revolutionslideshow')->__('random'),
				),
				array(
					'value' => 'slidehorizontal',
					'label' => Mage::helper('revolutionslideshow')->__('slidehorizontal'),
				),
				array(
					'value' => 'slidevertical',
					'label' => Mage::helper('revolutionslideshow')->__('slidevertical'),
				),
				array(
					'value' => 'papercut',
					'label' => Mage::helper('revolutionslideshow')->__('papercut'),
				),
				array(
					'value' => 'flyin',
					'label' => Mage::helper('revolutionslideshow')->__('flyin'),
				),
				array(
					'value' => 'turnoff',
					'label' => Mage::helper('revolutionslideshow')->__('turnoff'),
				),
				array(
					'value' => 'cube',
					'label' => Mage::helper('revolutionslideshow')->__('cube'),
				),
				array(
					'value' => '3dcurtain-vertical',
					'label' => Mage::helper('revolutionslideshow')->__('3dcurtain-vertical'),
				),
				array(
					'value' => '3dcurtain-horizontal',
					'label' => Mage::helper('revolutionslideshow')->__('3dcurtain-horizontal'),
				),
			),
			'note' => 'The appearance transition of this slide',
		));

		$fieldset->addField('masterspeed', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Masterspeed'),
			'required' => false,
			'name' => 'masterspeed',
			'note' => 'Set the Speed of the Slide Transition. Default 300, min:100 max:2000.'
		));
		$fieldset->addField('slotamount', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Slotamount'),
			'required' => false,
			'name' => 'slotamount',
			'note' => 'The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy.'
		));
		$fieldset->addField('link', 'text', array(
			'label' => Mage::helper('revolutionslideshow')->__('Slide Link'),
			'required' => false,
			'name' => 'link',
		));

		$data = array();
		$out = '';
		if (Mage::getSingleton('adminhtml/session')->getRokanthemerevolutionData()) {
			$data = Mage::getSingleton('adminhtml/session')->getRokanthemerevolutionData();
		} elseif (Mage::registry('rokanthemerevolution_data')) {
			$data = Mage::registry('rokanthemerevolution_data')->getData();
		}

		$fieldset->addField('image', 'image', array(
			'label' => Mage::helper('revolutionslideshow')->__('Image'),
			'required' => false,
			'name' => 'image',
		));

		$fieldset->addField('thumb', 'image', array(
			'label' => Mage::helper('revolutionslideshow')->__('Slide thumb'),
			'required' => false,
			'name' => 'thumb',
			'note' => 'An Alternative Source for thumbs. If not defined a copy of the background image will be used in resized form. ',
		));

		$fieldset->addField('text', 'textarea', array(
			'label'     => Mage::helper('revolutionslideshow')->__('Slide Content'),
			'required'  => false,
			'name'      => 'text',
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

		if (Mage::getSingleton('adminhtml/session')->getRokanthemerevolutionData()) {
			$form->setValues(Mage::getSingleton('adminhtml/session')->getRokanthemerevolutionData());
			Mage::getSingleton('adminhtml/session')->getRokanthemerevolutionData(null);
		} elseif (Mage::registry('rokanthemerevolution_data')) {
			$form->setValues(Mage::registry('rokanthemerevolution_data')->getData());
		}
		return parent::_prepareForm();
	}
}