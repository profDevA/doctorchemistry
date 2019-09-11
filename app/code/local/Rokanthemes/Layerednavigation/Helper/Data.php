<?php

class Rokanthemes_Layerednavigation_Helper_Data extends Mage_Core_Helper_Abstract {
    //check if is ajax request
    public function isAjax() {
        return (boolean) ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
    //delete white space
    public function trim($str) {
        $str = strip_tags($str);
        $str = str_replace('"', '', $str);
        return trim($str, " -");
    }
    
    public function getBaseUrlFilter() {
        $baseUrl = explode('?', Mage::helper('core/url')->getCurrentUrl());
        $baseUrl = $baseUrl[0];
        $baseUrl = trim($baseUrl); 
        return $baseUrl;
    }
    
    public function FormatPrice($price) {
        return 
        Mage::getModel('directory/currency')->formatTxt(
            $price,
            array('display' => Zend_Currency::NO_SYMBOL)
        );
    }
    
    public function prepareParams($params) {
        $url = "";
        foreach ($params as $key => $val) {
            if ($key == 'id') {
                continue;
            }
            if (strpos($key,'first')!== false) {
                continue;
            }
            if (strpos($key,'last')!== false) {
                continue;
            }
            if (strpos($key,'rate')!== false) {
                continue;
            }
            $url.='&'.$key.'='.$val;
        }
        return $url;
    }
    
    public function getStoreConfigField($field = null) {
       $fieldValue  = Mage::getStoreConfig('layerednavigation/layerfiler_config/'.$field);
       if($fieldValue)
           return $fieldValue;
       return false;
    }
    
     public function getJsRemoveItem() {
               $js = ' <script type ="text/javascript">
                $jq(function() {
                    //remove Item
                        $jq(".block-layered-nav .btn-remove").each(function(){
                            var urlRemove = $jq(this).attr("href");
                            $jq(this).attr("link",urlRemove);
                            $jq(this).bind("click",function(){
                                $jq(this).attr("onclick",ajaxFilter(urlRemove));
                                return false;
                            });
                        });
                     //remove all item
                    $jq(".block-layered-nav .actions a").each(function(){
                        var linkRemoveAll = $jq(this).attr("href");
                        $jq( this ).attr("link",linkRemoveAll);
                        $jq(this).bind("click",function(){
                            $jq(this).attr("onclick",ajaxFilter(linkRemoveAll));
                            return false;
                        });
                    });
                });
            </script>
            <div id ="wrapper">
                <div id="loading" style ="position: fixed;top: 50%;left: 50%;"></div>
            </div>
            ';
               return $js;
    }
    
   public function getToolbarForTagProductJs() {
        $js = '<script type ="text/javascript">
              $jq(".tags-list a").each(function(){
                        var TagUrl = $jq(this).attr("href");
                        $jq( this ).attr("link",TagUrl);
                        $jq(this).bind("click",function(){
                            $jq(this).attr("onclick",ajaxFilter(TagUrl));
                            return false;
                        });
              });
              //view mode product   
                    $jq(".view-mode a").each(function(){
                        var viewModeUrl = $jq(this).attr("href");
                        $jq( this ).attr("link",viewModeUrl);
                        $jq(this).bind("click",function(){
                            $jq(this).attr("onclick",ajaxFilter(viewModeUrl));
                            return false;
                        });
                    });

                    //sort by
                    $jq(".sort-by select").removeAttr("onchange");
                    $jq(".sort-by select").live("change",function(){
                        var selectUrl = $jq(".sorter select").val();
                        ajaxFilter(selectUrl);
                        return false;
                    });
                    //demention sort by

                    $jq(".sort-by a").each(function(){
                        var dementionUrl = $jq(this).attr("href");
                        $jq( this ).attr("link",dementionUrl);
                        $jq(this).bind("click",function(){
                            $jq(this).attr("onclick",ajaxFilter(dementionUrl));
                            return false;
                        });
                    });
                    //show per page
                    $jq(".limiter select").removeAttr("onchange");
                    $jq(".limiter select").live("change",function(){
                        var perUrl = $jq(this).val();
                        ajaxFilter(perUrl);
                        return false;
                    });
                    //pagination page
                    $jq(".pages a").each(function(){
                        var href = $jq(this).attr("href");
                        $jq( this ).attr("link",href);
                        $jq(this).bind("click",function(){
                            $jq(this).attr("onclick",ajaxFilter(href));
                            return false;
                        });
                    });

        </script>';
        return $js;
    }

}