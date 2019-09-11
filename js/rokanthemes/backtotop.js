
$jq(document).ready(function(){

	// hide #back-top first
	$jq("#back-top").hide();
	
	// fade in #back-top
	$jq(function () {
		$jq(window).scroll(function () {
			if ($jq(this).scrollTop() > 100) {
				$jq('#back-top').fadeIn();
			} else {
				$jq('#back-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$jq('#back-top').click(function () {
			$jq('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});
