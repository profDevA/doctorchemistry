jQuery(function($){
	if (typeof CONFIG_REVOLUTION !== 'undefined') {
		if ($.fn.cssOriginal!=undefined)   // CHECK IF fn.css already extended
			$.fn.css = $.fn.cssOriginal;
		$('.revolution-slideshow').revolution(CONFIG_REVOLUTION);
	}
});