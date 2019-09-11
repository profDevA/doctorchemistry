<?php
class Rokanthemes_Layerednavigation_Block_Tag_Popular extends Mage_Tag_Block_Popular
{
    
     public function __construct() {
      
        parent::__construct();  
        $this->setTemplate('layerednavigation/popular.phtml');
    }
//    
//    public function _toHtml1() {
//        return $this->TagList();
//        parent::_toHtml();
//    }
//    
//    public function TagList() {
//
//        $html = ' <div class="block block-tags">';
//
//        $html .= '<div class="block-title">';
//        $html .='         <strong><span>Popular Tags</span></strong>
//                </div>';
//        $html .= '<div class="block-content">
//                    <ul class="tags-list">';
//        foreach ($this->getTags() as $_tag):
//            $url = $_tag->getTaggedProductsUrl();
//            $onclick = "ajaxTagFilter('" . $url . "')";
//            $tagRatio = $_tag->getRatio()*70+75;
//            $style= "font-size:". $tagRatio. "%;";
//                    
//            $html .= '<li onclick ="' . $onclick . '"><a href="javascript:void(0)"  style = '.$style.' >' . $this->htmlEscape($_tag->getName()) . '</a></li>';
//        endforeach;
//        $html.=' </ul>
//                    <div class="actions">
//                        <a href="' . $this->getUrl('tag/list') . '">View All Tags</a>
//                    </div>
//                </div>';
//        $html .=' </div>';
//        return $html;
//    }
}