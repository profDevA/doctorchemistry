<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Revolution_Onoff
{
    public function toOptionArray()
    {
	    $options = array();
	    $options[] = array(
            'value' => 'on',
            'label' => 'On',
        );
        $options[] = array(
            'value' => 'off',
            'label' => 'Off',
        );

        return $options;
    }

}
