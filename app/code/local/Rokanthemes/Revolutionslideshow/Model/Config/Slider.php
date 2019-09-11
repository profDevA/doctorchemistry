<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Slider
{
    public function toOptionArray()
    {
	    $options = array();
        $options[] = array(
            'value' => 'revolution',
            'label' => 'Revolution slider',
        );

        return $options;
    }

}
