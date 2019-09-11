
$jq(document).ready(function(){
    $jq("#rk_menu_link ul li").each(function(){
        var url = document.URL;
        $jq("#rk_menu_link ul li a").removeClass("act");
        $jq('#rk_menu_link ul li a[href="'+url+'"]').addClass('act');
    }); 
    
    $jq('.rk_menu_no_child').hover(function(){
        $jq(this).addClass("active");
    },function(){
        $jq(this).removeClass("active");
    })
    
    $jq('.rk_menu').hover(function(){
        if($jq(this).attr("id") != "rk_menu_link"){
            $jq(this).addClass("active");
        }
    },function(){
        $jq(this).removeClass("active");
    })
    
    $jq('.rk_menu').hover(function(){
       /*show popup to calculate*/
       $jq(this).find('.popup').css('display','inline-block');
       
       /* get total padding + border + margin of the popup */
       var extraWidth       = 0
       var wrapWidthPopup   = $jq(this).find('.popup').outerWidth(true); /*include padding + margin + border*/
       var actualWidthPopup = $jq(this).find('.popup').width(); /*no padding, margin, border*/
       extraWidth           = wrapWidthPopup - actualWidthPopup;    
       
       /* calculate new width of the popup*/
       var widthblock1 = $jq(this).find('.popup .block1').outerWidth(true);
       var widthblock2 = $jq(this).find('.popup .block2').outerWidth(true);
       var new_width_popup = 0;
       if(widthblock1 && !widthblock2){
           new_width_popup = widthblock1;
       }
       if(!widthblock1 && widthblock2){
           new_width_popup = widthblock2;
       }
       if(widthblock1 && widthblock2){
            if(widthblock1 >= widthblock2){
                new_width_popup = widthblock1;
            }
            if(widthblock1 < widthblock2){
                new_width_popup = widthblock2;
            }
       }
       var new_outer_width_popup = new_width_popup + extraWidth;
       
       /*define top and left of the popup*/
       var wraper = $jq('.rk_explodemenu');
       var wWraper = wraper.outerWidth();
       var posWraper = wraper.offset();
       var pos = $jq(this).offset();
       
       var xTop = pos.top - posWraper.top + CUSTOMMENU_POPUP_TOP_OFFSET;
       var xLeft = pos.left - posWraper.left;
       if ((xLeft + new_outer_width_popup) > wWraper) xLeft = wWraper - new_outer_width_popup;

       $jq(this).find('.popup').css('top',xTop);
       $jq(this).find('.popup').css('left',xLeft);
       
       /*set new width popup*/
       $jq(this).find('.popup').css('width',new_width_popup);
       $jq(this).find('.popup .block1').css('width',new_width_popup);
       
       /*return popup display none*/
       $jq(this).find('.popup').css('display','none');
       
       /*show hide popup*/
       if(CUSTOMMENU_POPUP_EFFECT == 0) $jq(this).find('.popup').stop(true,true).slideDown('slow');
       if(CUSTOMMENU_POPUP_EFFECT == 1) $jq(this).find('.popup').stop(true,true).fadeIn('slow');
       if(CUSTOMMENU_POPUP_EFFECT == 2) $jq(this).find('.popup').stop(true,true).show();
    },function(){
       if(CUSTOMMENU_POPUP_EFFECT == 0) $jq(this).find('.popup').stop(true,true).slideUp();
       if(CUSTOMMENU_POPUP_EFFECT == 1) $jq(this).find('.popup').stop(true,true).fadeOut('slow');
       if(CUSTOMMENU_POPUP_EFFECT == 2) $jq(this).find('.popup').stop(true,true).hide('fast');
    })
});