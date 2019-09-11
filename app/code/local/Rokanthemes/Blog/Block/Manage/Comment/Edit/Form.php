<?php

class Rokanthemes_Blog_Block_Manage_Comment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                 'id'     => 'edit_form',
                 'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                 'method' => 'post',
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset(
            'comment_form', array('legend' => Mage::helper('blog')->__('Comment Information'))
        );

        $fieldset->addField(
            'user',
            'text',
            array(
                 'label' => Mage::helper('blog')->__('User'),
                 'name'  => 'user',
            )
        );

        $fieldset->addField(
            'email',
            'text',
            array(
                 'label' => Mage::helper('blog')->__('Email Address'),
                 'name'  => 'email',
            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                 'label'  => Mage::helper('blog')->__('Status'),
                 'name'   => 'status',
                 'values' => array(
                     array(
                         'value' => 1,
                         'label' => Mage::helper('blog')->__('Unapproved'),
                     ),
                     array(
                         'value' => 2,
                         'label' => Mage::helper('blog')->__('Approved'),
                     ),
                 ),
            )
        );

        $fieldset->addField(
            'comment',
            'editor',
            array(
                 'name'     => 'comment',
                 'label'    => Mage::helper('blog')->__('Comment'),
                 'title'    => Mage::helper('blog')->__('Comment'),
                 'style'    => 'width:700px; height:500px;',
                 'wysiwyg'  => false,
                 'required' => false,
            )
        );

        if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif (Mage::registry('blog_data')) {
            $form->setValues(Mage::registry('blog_data')->getData());
        }
        return parent::_prepareForm();
    }
}