/*global baseurl*/

/**
 * Twitter Bootstrap code. Needs to be executed with newer jquery version
 */
var $jq1 = jQuery.noConflict(true);
(function ($) {
	$(document).ready(function () {
		bootstrapCarousel();
	});
	
	function bootstrapCarousel() {
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
	}
	
}($jq1));


/**
 * Sidebar toggle code. Depends on jquery cookie plugin,
 * loaded by loadsize plugin
 */
(function($) {
	
	$(document).ready(function () {		
		bootstrapSidebarToggle();
	});
	
	function bootstrapSidebarToggle() {
		var sidebarToggle = $(document.createElement('i'));
		var sidebarToggleContainer = $(document.createElement('div'));
		sidebarToggleContainer.prepend(sidebarToggle);
		
		sidebarToggleContainer.attr("id", "sidebar-toggle");
		sidebarToggle.addClass("fa fa-bars");
			
		
		var toggled = toggledPreferences();
		toggleSidebar(toggled);
		
		sidebarToggleContainer.click(function() {			
			toggled = !toggled;
			toggleSidebar(toggled);
		});
		
		
		$(sidebarToggleContainer).appendTo("#navbar");
		$(sidebarToggleContainer).clone(true).prependTo("#rightSidebar");
		
		$('#body').addClass('collapsable');
	}
	
	function toggleSidebar(toggled) {
		var body = $('#body');
		toggledPreferences(toggled);
		body.toggleClass("hide-sidebar", toggled);
	}
	
	/**
	 * Depends on jquery.cookie
	 */
	function toggledPreferences(toggled) {
		var sidebarToggleCookie = "sidebarToggle";
		if (toggled === undefined) {
			return readToggledPreferences();
		} else {
			setToggled(toggled)
		}
		
		function readToggledPreferences() {
			if ($.cookie != undefined) {
				var toggled = $.cookie(sidebarToggleCookie) === "true" ? true : false;
				if (toggled !== undefined) {
					return toggled;
				} else {
					return false;
				}
			}
		}
		
		function setToggled(toggled) {
			jQuery.cookie(sidebarToggleCookie, toggled, { path: baseUrl });
		}
	}	
}($));
