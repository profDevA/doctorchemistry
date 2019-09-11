<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Show
{
    public function toOptionArray()
    {
	    $options = array();
	    $options[] = array(
            'value' => 'home',
            'label' => 'HomePage Only',
        );
        $options[] = array(
            'value' => 'all',
            'label' => 'All Pages',
        );

        return $options;
    }

}
