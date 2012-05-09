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
});