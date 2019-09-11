<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Revolution_Shadow
{
    public function toOptionArray()
    {
	    $options = array();
        $options[] = array(
            'value' => '0',
            'label' => '0',
        );
	    $options[] = array(
            'value' => '1',
            'label' => '1',
        );
        $options[] = array(
            'value' => '2',
            'label' => '2',
        );
        $options[] = array(
            'value' => '3',
            'label' => '3',
        );

        return $options;
    }

}