var $jq1 = jQuery.noConflict(true);
(function ($) {
	$(document).ready(function () {
		//$('.carousel').carousel();
		$(".carousel").swipe({
			swipe : function(event, direction, distance,
					duration, fingerCount, fingerData) {
				if (direction == 'left')
					$(this).carousel('next');
				if (direction == 'right')
					$(this).carousel('prev');
			},
			allowPageScroll : "vertical"
		});
	});
}($jq1))