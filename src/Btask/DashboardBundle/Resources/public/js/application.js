$(function() {

	// Active the dropdown method
	$('.dropdown-toggle').dropdown();

	// Load the dummies data in the typeahead
	var alCities = ['Baltimore', 'Boston', 'New York', 'Tampa Bay', 'Toronto', 'Chicago', 'Cleveland', 'Detroit', 'Kansas City', 'Minnesota', 'Los Angeles', 'Oakland', 'Seattle', 'Texas'].sort();
	$('.underling').typeahead({source: alCities, items:5});
});