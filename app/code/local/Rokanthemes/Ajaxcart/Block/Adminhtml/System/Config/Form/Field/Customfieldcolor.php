<?php
class Rokanthemes_Ajaxcart_Block_Adminhtml_System_Config_Form_Field_Customfieldcolor extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $output=parent::_getElementHtml($element);
        if (!Mage::registry('mColorPicker')) {
            $output.='
            <script type="text/javascript" src="'.$this->getJsUrl('rokanthemes/ma.jq.slide.js').'"></script>
            <script type="text/javascript" src="'.$this->getJsUrl('rokanthemes/mColorPicker.js').'"></script>
            <script type="text/javascript">
                  var image = "'.$this->getJsUrl('rokanthemes/images/').'";
                 $jq.fn.mColorPicker.defaults.imageFolder= image;
            </script>
            ';
            Mage::register('mColorPicker', 1);
        }
		$output .= '
        <script type="text/javascript">
            jQuery(function(){
                 $jq("#'.$element->getHtmlId().'").attr("data-hex", true).mColorPicker();
            })
        </script> ';
        return $output;
    }
}