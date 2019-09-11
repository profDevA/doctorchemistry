<?php

class Rokanthemes_Revolutionslideshow_Model_Config_Revolution_Navbar
{
    public function toOptionArray()
    {
	    $options = array();
        $options[] = array(
            'value' => 'none',
            'label' => 'none',
        );
	    $options[] = array(
            'value' => 'bullet',
            'label' => 'bullet',
        );
        $options[] = array(
            'value' => 'thumb',
            'label' => 'thumb',
        );

        return $options;
    }

}
