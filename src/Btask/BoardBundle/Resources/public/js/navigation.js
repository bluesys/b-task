/**
 * Import navigation element
 *
 * @author geoffroy.perriard@gmail.com
 *
 */

// Display all workgroups of the logged user
function getWorkgroups() {
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroups_show'),
        cache: true,
        success: function(data){
            $('#navigation #workgroups').html(data);

            $('#workgroups .workgroup .controls ul li:first-child a').click(function (){
                var workgroupId = $(this).closest('.workgroup').data('id');

                // Hide the title, controls button and show the form
                $(this).closest('.header').children().hide();
                $('#workgroup-form-edit-' + workgroupId).show();

                $('#workgroup-form-edit-' + workgroupId).submit(function (){
                    updateWorkgroup(workgroupId, $(this));
                    $(this).closest('.header').children().show();
                    $(this).hide();
                    return false;
                });
            });
        }
    });
}

// Update a workgroup
function updateWorkgroup(id, form) {
    // Get the form
    var $form = form;

    if ($form) {
        $.ajax({
            type: "POST",
            url: Routing.generate('BtaskBoardBundle_workgroup_update', { id: id }),
            data: $form.serialize(),
            cache: false,
            success: function(data, textStatus, xhr){

                // Refresh all workgroups
                if (xhr.status == 200) {
                    getWorkgroups();
                }
            }
        });
    }
}

// Display a form to create a workgroup
function getWorkgroupCreateForm() {
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroup_create'),
        cache: true,
        success: function(data){
            $('#workgroup-create').append(data);

            // Hide the button and display the form
            $('#workgroup-create a.btn').click(function () {
                $(this).hide();
                $('#workgroup-form-create').show();
            });

            // Send the workgroup
            $('#workgroup-form-create').submit(function (){
                // Get the form
                var $form = $(this);

                // Hide the form and display the button
                $form.hide();
                $('#workgroup-create a.btn').show();

                $.ajax({
                    type: "POST",
                    url: Routing.generate('BtaskBoardBundle_workgroup_create'),
                    data: $form.serialize(),
                    cache: false,
                    success: function(data, textStatus, xhr){

                        // Refresh all workgroups
                        if (xhr.status == 200) {
                            getWorkgroups();
                        }
                    }
                });

                return false;
            });
        }
    });
}
	/*
	// Check if desktop terminal
	if ($(document).width() > 960) {

		// Define the navigation top position with the height of the navbar, the dashboard and the padding-bottom of the posts-it section
		var navbarHeight = $('.navbar.navbar-fixed-top').outerHeight() + parseInt($('#posts-it').css('padding-bottom').replace("px", ""));
		var defaultHeight = $('#dashboard').outerHeight() + navbarHeight;
		$('#navigation').css({ top: defaultHeight });


		$(window).scroll(function () {
			var scrollPosition = $(window).scrollTop();

			// Append the navigation the dashboard until the dashboard isn't visible
			if (defaultHeight - scrollPosition > navbarHeight) {
				$('#navigation').css({ top: defaultHeight - scrollPosition });
			}
			else {
				$('#navigation').css({ top: $('.navbar.navbar-fixed-top').outerHeight() + parseInt($('#posts-it').css('padding-bottom').replace("px", "")) });
			}
		});
	}
	*/
