$(function() {

	// Active the dropdown method
	$('.dropdown-toggle').dropdown();

	// Load the dummies data in the typeahead
	var alCities = ['Baltimore', 'Boston', 'New York', 'Tampa Bay', 'Toronto', 'Chicago', 'Cleveland', 'Detroit', 'Kansas City', 'Minnesota', 'Los Angeles', 'Oakland', 'Seattle', 'Texas'].sort();
	$('.underling').typeahead({source: alCities, items:5});

	// Hide the dashboard on click in the nav button (only for mobile)
	var dashboardToggle = false;
	$('.dashboard-toggle a').click(function () {
		if (dashboardToggle == false) {
			$('#dashboard').css({ display: "none" });
			dashboardToggle = true;
		}
		else {
			$('#dashboard').css({ display: "block" });
			dashboardToggle = false;
		}
	});

	$('.post-it-add a').click(function () {
		$('#dashboard #posts-it .add').css({ display: "inline-block" });
	});

	$('#dashboard #posts-it .add .close').click(function () {
		$('#dashboard #posts-it .add').css({ display: "none" });
	});

	var notificationsItToggle = true;
	$('#dashboard .title:first a').click(function () {
		if (notificationsItToggle == false) {
			$('#dashboard #notifications').css({ display: "none" });
			notificationsItToggle = true;
		}
		else {
			$('#dashboard #notifications').css({ display: "block" });
			notificationsItToggle = false;
		}
	});

	var postItToggle = true;
	$('#dashboard .title:last a').click(function () {
		if (postItToggle == false) {
			$('#dashboard #posts-it').css({ display: "none" });
			postItToggle = true;
		}
		else {
			$('#dashboard #posts-it').css({ display: "block" });
			postItToggle = false;
		}
	});

	// Get the height of the navbar, the dashboard and the padding-bottom of the posts-it section
	var defaultHeight = $('#dashboard').outerHeight() + $('.navbar.navbar-fixed-top').outerHeight() + parseInt($('#posts-it').css('padding-bottom').replace("px", ""));
	$('#navigation').css({ top: dashboardHeight });

	$(window).scroll(function () {
		var topper = $(window).scrollTop();

		if (defaultHeight - topper > 60) {
			$('#navigation').css({ top: defaultHeight - topper });
		}
		else {
			$('#navigation').css({ top: $('.navbar.navbar-fixed-top').outerHeight() + parseInt($('#posts-it').css('padding-bottom').replace("px", "")) });
		}
	});
});