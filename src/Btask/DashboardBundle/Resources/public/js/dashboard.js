/*
 * Dashboard effects
 *
 * @author geoffroy.perriard@gmail.com
 *
 */

$(function() {

	// Show the form to create a post-it in dashboard
	$('.plus').click(function () {
		$('.post-it.form').css({ 'display' : 'inline-block' });
	});

	// Hide the form to create a post-it in dashboard
	$('.post-it.form .close').click(function () {
		$('.post-it.form').css({ 'display' : 'none' });
	});

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

	// Hide notifications on click in the nav button (only for mobile)
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

	// Hide post-it on click in the nav button (only for mobile)
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
});