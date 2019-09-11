<?php
class Rokanthemes_Related_Model_Layout_Generate_Observer {
    private function __getHeadBlock() {
        return Mage::getSingleton('core/layout')->getBlock('rokanthemes_related_head');
		
    }
}
