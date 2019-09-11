<?php
class Rokanthemes_Explodemenu_Block_Explodemenu extends Mage_Catalog_Block_Navigation
{
    const CUSTOM_BLOCK_TEMPLATE = 'rk_menu_idcat_%d';
    
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function drawCustomMenuItem($category, $level = 0, $last = false,$item)
    {
        if (!$category->getIsActive()) return '';
        // var_dump($category->getData());

        $html = array();
        $blockHtml = '';
        $id = $category->getId();
        // --- Static Block ---
        $blockId = sprintf('rk_menu_idcat_%d', $id); // --- static block key
        $blockHtml = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        /*check block right*/
        $blockIdRight = sprintf('rk_menu_idcat_%d_right', $id); // --- static block key
        $blockHtmlRight = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockIdRight)->toHtml();
        if($blockHtmlRight) $blockHtml = $blockHtmlRight;
        // --- Sub Categories ---
        $activeChildren = $this->getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        // --- Popup functions for show ---
        $drawPopup = ($blockHtml || count($activeChildren));
        if ($drawPopup)
        {
            $html[] = '<div id="rk_menu' . $id . '" class="rk_menu' . $active . ' nav-' .$item. '">';
        }
        else
        {
            $html[] = '<div id="rk_menu' . $id . '" class="rk_menu' . $active . ' nav-' .$item. ' rk_menu_no_child">';
        }


        // --- Top Menu Item ---
        $html[] = '<div class="parentMenu">';
        $html[] = '<a href="'.$this->getCategoryUrl($category).'">';
        $name = $this->escapeHtml($category->getName());
        $name = str_replace(' ', '&nbsp;', $name);
        $html[] = '<span>' . $name . '</span>';
        $html[] = '</a>';
        $label  = Mage::getModel("catalog/category")->load($category->getId())->getCatLabel();
        if($label) $html[] = '<span class="'.$label.'">'.$this->__($label).'</span>';
        $html[] = '</div>';
        
        // --- Add Popup block (hidden) ---
        if ($drawPopup)
        {
            // --- Popup function for hide ---
            $html[] = '<div id="popup' . $id . '" class="popup" style="display: none; width: 1228px;">';
            // --- draw Sub Categories ---
            if (count($activeChildren))
            {
                $html[] = '<div class="block1" id="block1' . $id . '">';
                $html[] = $this->drawColumns($activeChildren, $id);
                if ($blockHtml && $blockHtmlRight)
                {
                    $html[] = '<div class="column blockright last">';
                    $html[] = $blockHtml;
                    $html[] = '</div>';
                }
                $html[] = '<div class="clearBoth"></div>';
                $html[] = '</div>';
            }
            // --- draw Custom User Block ---
            if ($blockHtml && !$blockHtmlRight)
            {
                $html[] = '<div class="block2" id="block2' . $id . '">';
                $html[] = $blockHtml;
                $html[] = '</div>';
            }
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
        $columns = (int)Mage::getStoreConfig('explodemenu/columns/count');
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
        $blockId = sprintf('rk_menu_idcat_%d', $id); // --- static block key
        $blockHtml = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        /*Check blog right*/
        $blockIdRight = sprintf('rk_menu_idcat_%d_right', $id); // --- static block key
        $blockHtmlRight = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockIdRight)->toHtml();
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
            $html.= '<div class="column'. $classSpecial . ' col' . ($key+1) . '">';
            $html.= $this->drawMenuItem($value, 1, $columChunk);
            $html.= '</div>';
        }
        return $html;
    }

    protected function getActiveChildren($parent, $level)
    {
        $activeChildren = array();
        // --- check level ---
        $maxLevel = (int)Mage::getStoreConfig('explodemenu/general/max_level');
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
       
        if ($count) $target = array_chunk($target, ceil($count / $num));
        
        $target = array_pad($target, $num, array());
        #return $target;
         
        if ((int)Mage::getStoreConfig('explodemenu/columns/integrate') && count($target))
        {
            // --- combine consistently numerically small column ---
            // --- 1. calc length of each column ---
            $max = 0; $columnsLength = array();
            foreach ($target as $key => $child)
            {
                $count = 0;
                $this->_countChild($child, 1, $count);
                
                if ($max < $count) $max = $count;
                $columnsLength[$key] = $count;
            }
            
            // --- 2. merge small columns with next ---
            $xColumns = array(); $column = array(); $cnt = 0;
            $xColumnsLength = array(); $k = 0;
            
            foreach ($columnsLength as $key => $count)
            {
                $cnt+= $count;
                if ($cnt > $max && count($column))
                {
                    $xColumns[$k] = $column;
                    $xColumnsLength[$k] = $cnt - $count;
                    $k++; $column = array(); $cnt = $count;
                }
                $column = array_merge($column, $target[$key]);
            }
            $xColumns[$k] = $column;
            $xColumnsLength[$k] = $cnt - $count;
            // --- 3. integrate columns of one element ---
            $target = $xColumns; $xColumns = array(); $nextKey = -1;
            if ($max > 1 && count($target) > 1)
            {
                foreach($target as $key => $column)
                {
                    if ($key == $nextKey) continue;
                    if ($xColumnsLength[$key] == 1)
                    {
                        // --- merge with next column ---
                        $nextKey = $key + 1;
                        if (isset($target[$nextKey]) && count($target[$nextKey]))
                        {
                            $xColumns[] = array_merge($column, $target[$nextKey]);
                            continue;
                        }
                    }
                    $xColumns[] = $column;
                }
                $target = $xColumns;
            }
        }
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

    public function drawMenuItem($children, $level = 1, $columChunk=null)
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
        $catsid = Mage::getStoreConfig('explodemenu/general/catsid');
        $arr_catsid = array();
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
                    $html.= $this->drawMenuItem($activeChildren, $level + 1);
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
        //$blockId = sprintf('rk_explodemenu_%d', $id); // --- static block key
        $block = Mage::getModel('cms/block')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($blockId);
        //$title = $block->getTitle();
        $id = '_'.$blockId;
        //echo $isActive = $block->getIsActive();die();
        
        $blockHtml = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        $drawPopup = $blockHtml;
        if ($drawPopup)
        {
            $html[] = '<div id="rk_menu' . $id . '" class="rk_menu">';
        }
        else
        {
            $html[] = '<div id="rk_menu' . $id . '" class="rk_menu">';
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
            $html[] = '<div id="popup' . $id . '" class="popup cmsblock" style="display: none; width: 904px;">';
            if ($blockHtml)
            {
                $html[] = '<div class="block2" id="block2' . $id . '">';
                $html[] = $blockHtml;
                $html[] = '</div>';
            }
            $html[] = '</div>';
        }
        $html[] = '</div>';
        $html = implode("\n", $html);
        return $html;
    }
}