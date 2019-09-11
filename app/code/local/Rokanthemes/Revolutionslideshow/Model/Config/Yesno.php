<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Yesno
{
    public function toOptionArray()
    {
	    $options = array();
	    $options[] = array(
            'value' => 'true',
            'label' => 'Yes',
        );
        $options[] = array(
            'value' => 'false',
            'label' => 'No',
        );

        return $options;
    }

}
