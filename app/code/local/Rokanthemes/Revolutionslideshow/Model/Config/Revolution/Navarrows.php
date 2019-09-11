<?php


class Rokanthemes_Revolutionslideshow_Model_Config_Revolution_Navarrows
{
    public function toOptionArray()
    {
	    $options = array();
        $options[] = array(
            'value' => 'solo',
            'label' => 'solo',
        );
        $options[] = array(
            'value' => 'nexttobullets',
            'label' => 'nexttobullets',
        );

        return $options;
    }

}
