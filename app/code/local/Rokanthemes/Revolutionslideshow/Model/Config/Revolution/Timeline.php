<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Revolution_Timeline
{
    public function toOptionArray()
    {
	    $options = array();
	    $options[] = array(
            'value' => 'top',
            'label' => 'top',
        );
        $options[] = array(
            'value' => 'bottom',
            'label' => 'bottom',
        );

        return $options;
    }

}
