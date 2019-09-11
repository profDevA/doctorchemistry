<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Autoheight
{
    public function toOptionArray()
    {
	    $options = array();
	    $options[] = array(
            'value' => 'container',
            'label' => 'adjust height per slide',
        );
        $options[] = array(
            'value' => 'calc',
            'label' => 'calculate the tallest slide and use it',
        );

        return $options;
    }

}
