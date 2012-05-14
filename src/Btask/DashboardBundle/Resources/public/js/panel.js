/*
 * Edit panel animation
 *
 * @author geoffroy.perriard@gmail.com
 *
 */

$(function() {
	// Display the panel for dashboard, tasks and notes editing
	var editPanelToggle = false;
	$('.btn.edit').click(function () {

		var editPanelWidth = $('#navigation').outerWidth();

		// Show edit panel
		if (editPanelToggle == false) {
			$('#panel').animate({ 'right': 0 }, 400, function () {});
			editPanelToggle = true;
		}

		// Hide edit panel
		else {
			$('#panel').animate({ 'right': -editPanelWidth }, 400, function () {});
			editPanelToggle = false;
		}
	});

	// Display the panel for navigation editing
	var navigationEditPanelToggle = false;
	$('.btn-group').click(function () {

		var editPanelWidth = $('#panel').outerWidth() * 2;
		
		// Show edit panel
		if (navigationEditPanelToggle == false) {
			$('#panel').animate({ 'right': 0 }, 400, function () {});
			navigationEditPanelToggle = true;
		}

		// Hide edit panel
		else {
			$('#panel').animate({ 'right': -panelWidth }, 400, function () {});
			navigationEditPanelToggle = false;
		}
	});

});
