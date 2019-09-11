<?php
class Rokanthemes_Verticalmenu_Block_Verticalmenu extends Mage_Catalog_Block_Navigation
{
    const CUSTOM_BLOCK_TEMPLATE = 'pt_menu_idcat_%d';
    
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function drawCustomMenuItem($category, $level = 0, $last = false, $class='')
    {
        if (!$category->getIsActive()) return '';

        $html = array();
        $blockHtml = '';
        $id = $category->getId();
        // --- Static Block ---
        $blockId = sprintf('pt_menu_idcat_%d', $id); // --- static block key
        $blockHtml = $this->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        /*check block right*/
        $blockIdRight = sprintf('pt_menu_idcat_%d_right', $id); // --- static block key
        $blockHtmlRight = $this->getLayout()->createBlock('cms/block')->setBlockId($blockIdRight)->toHtml();
        if($blockHtmlRight) $blockHtml = $blockHtmlRight;
        
        $rightmenu = Mage::getStoreConfig('verticalmenu/general/rightmenu');
        // --- Sub Categories ---
        $activeChildren = $this->getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        // --- Popup functions for show ---
        $drawPopup = ($blockHtml || count($activeChildren));
        if ($drawPopup)
        {  
            $html[] = '<div id="rokanthemes-pt-menu' . $id . '" class="rokanthemes-pt-menu' . $active . ' '.$class.' " >';
        }
        else
        {
            $html[] = '<div id="rokanthemes-pt-menu' . $id . '" class="rokanthemes-pt-menu' . $active .' '.$class.'">';
        }
		$hasSubMenu = '';
		if (!$drawPopup){ $hasSubMenu = ' noSubMenu'; } else { $hasSubMenu = ''; }
        // --- Top Menu Item ---
        $html[] = '<div class="rokanthemes-parent-menu'.$hasSubMenu.'">';
        $html[] = '<a href="'.$this->getCategoryUrl($category).'">';
        $name = $this->escapeHtml($category->getName());
        $name = str_replace(' ', '&nbsp;', $name);
		$thumbnail = Mage::getModel('catalog/category')->load($category->getId())->getThumbnail();
		if($thumbnail) {
			//$thumbnail_url = Mage::getBaseUrl('media').'catalog/category/'.$thumbnail;
			$thumbnail_url_resize = $this->getResizedImage(21,21,100,$thumbnail);
			 $html[] = '<span class="thumbnail-imge"><img src="'.$thumbnail_url_resize.'"  alt= "'.$category->getName().'"/></span><span>' . $name . '</span>';
		} else {
			$html[] = '<span>' . $name . '</span>';
		}
        $html[] = '</a>';
        $html[] = '</div>';
        
        // --- Add Popup block (hidden) ---
        if ($drawPopup)
        {
            //$html[] = ($rightmenu) ? '<div class="wrap-popup">' : '';
            $html[] = '<div class="wrap-rokanthemes-wrap-popup">';
            // --- Popup function for hide ---
	    $html[] = '<div id="popup' . $id . '" class="popup" >';
	    $html[] = '<div class="box-popup">';
            
            // --- draw Sub Categories ---
            if (count($activeChildren))
            {
                $html[] = '<div class="block1">';
                $html[] = $this->drawColumns($activeChildren, $id);
                if ($blockHtml && $blockHtmlRight)
                {
                    $html[] = '<div class="column blockright last"  style="float:left;">';
                    $html[] = $blockHtml;
                    $html[] = '</div>';
                }
                $html[] = '<div class="clearBoth"></div>';
                $html[] = '</div>';
            }
            // --- draw Custom User Block ---
            if ($blockHtml && !$blockHtmlRight)
            {
                $html[] = '<div class="block2">';
                $html[] = $blockHtml;
                $html[] = '</div>';
            }
            $html[] = '</div>';
	    $html[] = '</div>';
            $html[] = '</div>';
        }
        
        $html[] = '</div>';
        $html = implode("\n", $html);
        return $html;
    }

    public function drawColumns($children, $id)
    {
        $html = '';
        // --- explode by columns ---
        $columns = (int)Mage::getStoreConfig('verticalmenu/columns/count');
        if ($columns < 1) $columns = 1;
        $chunks = $this->explodeByColumns($children, $columns);
        $columChunk = count($chunks);
        // --- draw columns ---
        $classSpecial = '';
        $keyLast = 0;
        foreach ($chunks as $key => $value){
            if(count($value)) $keyLast++;
        }
        $blockHtml = '';
        $blockId = sprintf('pt_menu_idcat_%d', $id); // --- static block key
        $blockHtml = $this->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        /*Check blog right*/
        $blockIdRight = sprintf('pt_menu_idcat_%d_right', $id); // --- static block key
        $blockHtmlRight = $this->getLayout()->createBlock('cms/block')->setBlockId($blockIdRight)->toHtml();
        if($blockHtmlRight) $blockHtml = $blockHtmlRight;
        foreach ($chunks as $key => $value)
        {
            if (!count($value)) continue;
            if($key == $keyLast - 1){
                $classSpecial = ($blockHtmlRight && $blockHtml)? '':' last';
            }elseif($key == 0){
                $classSpecial = ' first';
            }else{
                $classSpecial = '';
            }
            $html.= '<div class="column'. $classSpecial . ' col' . ($key+1) . '" style="float:left;">';
            $html.= $this->drawMenuItem($value, 1, $columChunk);
            $html.= '</div>';
        }
        return $html;
    }

    protected function getActiveChildren($parent, $level)
    {
        $activeChildren = array();
        // --- check level ---
        $maxLevel = (int)Mage::getStoreConfig('verticalmenu/general/max_level');
        if ($maxLevel > 0)
        {
            if ($level >= ($maxLevel - 1)) return $activeChildren;
        }
        // --- / check level ---
        if (Mage::helper('catalog/category_flat')->isEnabled())
        {
            $children = $parent->getChildrenNodes();
            $childrenCount = count($children);
        }
        else
        {
            $children = $parent->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        if ($hasChildren)
        {
            foreach ($children as $child)
            {
                if ($child->getIsActive())
                {
                    array_push($activeChildren, $child);
                }
            }
        }
        return $activeChildren;
    }

   function partition_element(Array $list, $p) {
		$listlen = count($list);
		$partlen = floor($listlen / $p);
		$partrem = $listlen % $p;
		$partition = array();
		$mark = 0;
		for($px = 0; $px < $p; $px ++) {
			$incr = ($px < $partrem) ? $partlen + 1 : $partlen;
			$partition[$px] = array_slice($list, $mark, $incr);
			$mark += $incr;
		}
		return $partition;
	}

    private function explodeByColumns($target, $num)
    {
        $countChildren = 0;
        foreach ($target as $cat => $childCat)
        {
            $activeChildCat = $this->getActiveChildren($childCat, 0);
            if($activeChildCat){
                $countChildren++;
            }
        }
        if($countChildren == 0){ 
            $num = 1; 
        }
        $count = count($target);
       
        if ($count) 
        $target =  $this->partition_element($target, $num);
     
        return $target;
    }

    private function _countChild($children, $level, &$count)
    {
        foreach ($children as $child)
        {
            if ($child->getIsActive())
            {
                $count++; $activeChildren = $this->getActiveChildren($child, $level);
                if (count($activeChildren) > 0) $this->_countChild($activeChildren, $level + 1, $count); 
            }
        }
    }

    public function drawMenuItem($children, $level = 1, $columChunk)
    {
        $html = '<div class="itemMenu level' . $level . '">';
        $keyCurrent = $this->getCurrentCategory()->getId();
        $countChildren = 0;
        $ClassNoChildren = '';
        foreach ($children as $child)
        {
            $activeChildCat = $this->getActiveChildren($child, 0);
            if($activeChildCat){
                $countChildren++;
            }
        }
        if($countChildren == 0 && $columChunk == 1){ 
            $ClassNoChildren = ' nochild'; 
        }
        $catsid = Mage::getStoreConfig('verticalmenu/general/catsid');
        if($catsid){    
            if(stristr($catsid, ',') === FALSE) {
                $arr_catsid =  array(0 => $catsid);
            }else{
                $arr_catsid = explode(",", $catsid);
            }
        }
//        echo "<pre>";
//        print_r($arr_catsid);die();
        foreach ($children as $child)
        {
            
            if ($child->getIsActive())
            {
                
                
                // --- class for active category ---
                $active = '';
                if ($this->isCategoryActive($child))
                {
                    $active = ' actParent';
                    if ($child->getId() == $keyCurrent) $active = ' act';
                }
                
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                $name = str_replace(' ', '&nbsp;', $name);
                if( in_array($child->getId(),$arr_catsid) ){
                    $html.= '<h4 class="itemMenuName level' . $level . $active . $ClassNoChildren . '"><span>' . $name . '</span></h4>';
                }else{
                    $html.= '<a class="itemMenuName level' . $level . $active . $ClassNoChildren . '" href="' . $this->getCategoryUrl($child) . '"><span>' . $name . '</span></a>';
                }
                $activeChildren = $this->getActiveChildren($child, $level);
                if (count($activeChildren) > 0)
                {
                    $html.= '<div class="itemSubMenu level' . $level . '">';
                    $html.= $this->drawMenuItem($activeChildren, $level + 1,$columChunk);
                    $html.= '</div>';
                }
            }
        }
        $html.= '</div>';
        return $html;
    }
    
    public function drawCustomMenuBlock($blockId)
    {

        $html = array();
        // --- Static Block ---
        //$blockId = sprintf('pt_verticalmenu_%d', $id); // --- static block key
        $block = Mage::getModel('cms/block')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($blockId);
        //$title = $block->getTitle();
        $id = '_'.$blockId;
        //echo $isActive = $block->getIsActive();die();
        
        $blockHtml = $this->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        $rightmenu = Mage::getStoreConfig('verticalmenu/general/rightmenu');
        $drawPopup = $blockHtml;
        if ($drawPopup)
        {
            $html[] = '<div id="pt_menu' . $id . '" class="pt_menu" >';
        }
        else
        {
            $html[] = '<div id="pt_menu' . $id . '" class="pt_menu">';
        }
        // --- Top Menu Item ---
        $html[] = '<div class="parentMenu">';
//        $html[] = '<a href="#">';
        $name = $block->getTitle();
        $name = str_replace(' ', '&nbsp;', $name);
        $html[] = '<span class="block-title">' . $name . '</span>';
//        $html[] = '</a>';
        $html[] = '</div>';
        // --- Add Popup block (hidden) ---
        if ($drawPopup)
        {
            // --- Popup function for hide ---
            $html[] = '<div class="wrap-popup">';
	    $html[] = '<div id="popup' . $id . '" class="popup" >';
	    $html[] = '<div class="box-popup">';
            
            if ($blockHtml)
            {
                $html[] = '<div class="block2">';
                $html[] = $blockHtml;
                $html[] = '</div>';
            }
            $html[] = '</div>';
	    $html[] = '</div>';
            $html[] = '</div>';
        }
        $html[] = '</div>';
        $html = implode("\n", $html);
        return $html;
    }
	
	public function getResizedImage($width = null, $height = null, $quality = 100,$image= NULL) {
		if (!$image)
			return false;
	 
			$imageUrl = Mage::getBaseDir('media').DS."catalog".DS."category".DS.$image;
			if (!is_file( $imageUrl ))
			return false;
	 
			$imageResized = Mage::getBaseDir('media').DS."catalog".DS."category".DS."cat_resized".DS.$image;
			if (!file_exists($imageResized) && file_exists($imageUrl) || file_exists($imageUrl) && filemtime($imageUrl) > filemtime($imageResized)):
			$imageObj = new Varien_Image($imageUrl);
           	$imageObj->constrainOnly(TRUE);
			$imageObj->keepAspectRatio(FALSE);
			$imageObj->keepFrame(FALSE);
			$imageObj->quality(100);
            $imageObj->keepTransparency(true);
            $imageObj->resize($width, $height);
			$imageObj->save($imageResized);
			
		endif;
	 
		if(file_exists($imageResized)){
			return Mage::getBaseUrl('media' )."catalog/category/cat_resized/".$image;
		}
		else{
			return $imageUrl;
		}
	}
	
	
}