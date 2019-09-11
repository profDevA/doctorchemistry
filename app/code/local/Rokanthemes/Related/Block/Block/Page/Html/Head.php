<?php
class Rokanthemes_Related_Block_Page_Html_Head extends Mage_Page_Block_Html_Head
{
    public function addFirst($type, $name, $params=null, $if=null, $cond=null)
    {
        $_item = array(
            $type.'/'.$name => array(
                'type'   => $type,
                'name'   => $name,
                'params' => $params,
                'if'     => $if,
                'cond'   => $cond)
            );
        $_head = $this->__getHeadBlock();
        if (is_object($_head)) {
            $_itemList = $_head->getData('items');
            $_itemList = array_merge($_item, $_itemList);

            $_head->setData('items', $_itemList);
        }
    }

    public function addBefore($type, $name, $before=null, $params=null, $if=null, $cond=null)
    {
        if ($before) {
            $_backItem = array();
            $_searchStatus = false;
            $_searchKey = $type.'/'.$before;
            $_head = $this->__getHeadBlock();
            if (is_object($_head)) {
                $_itemList = $_head->getData('items');
                if (is_array($_itemList)) {
                    $keyList = array_keys($_itemList);
                    foreach ($keyList as &$_key) {
                        if ($_searchKey == $_key) {
                            $_searchStatus = true;
                        }

                        if ($_searchStatus) {
                            $_backItem[$_key] = $_itemList[$_key];
                            unset($_itemList[$_key]);
                        }
                    }
                }

                if ($type==='skin_css' && empty($params)) {
                    $params = 'media="all"';
                }
                $_itemList[$type.'/'.$name] = array(
                    'type'   => $type,
                    'name'   => $name,
                    'params' => $params,
                    'if'     => $if,
                    'cond'   => $cond,
                );

                if (is_array($_backItem)) {
                    $_itemList = array_merge($_itemList, $_backItem);
                }
                $_head->setData('items', $_itemList);
            }
        }
    }

    public function addAfter($type, $name, $after=null, $params=null, $if=null, $cond=null)
    {
        if ($after) {
            $_backItem = array();
            $_searchStatus = false;
            $_searchKey = $type.'/'.$after;
            $_head = $this->__getHeadBlock();
            if (is_object($_head)) {
                $_itemList = $_head->getData('items');
                if (is_array($_itemList)) {
                    $keyList = array_keys($_itemList);
                    foreach ($keyList as &$_key) {
                        if ($_searchStatus) {
                            $_backItem[$_key] = $_itemList[$_key];
                            unset($_itemList[$_key]);
                        }
                        if ($_searchKey == $_key) {
                            $_searchStatus = true;
                        }
                    }
                }

                if ($type==='skin_css' && empty($params)) {
                    $params = 'media="all"';
                }
                $_itemList[$type.'/'.$name] = array(
                    'type'   => $type,
                    'name'   => $name,
                    'params' => null,
                    'if'     => null,
                    'cond'   => null,
                );

                if (is_array($_backItem)) {
                    $_itemList = array_merge($_itemList, $_backItem);
                }
                $_head->setData('items', $_itemList);
            }
        }
    }


    private function __getHeadBlock() {
        return Mage::getSingleton('core/layout')->getBlock('head');
    }
}
