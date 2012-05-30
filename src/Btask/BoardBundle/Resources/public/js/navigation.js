/**
 * Import navigation element
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 *
 */

// Display all workgroups of the logged user
function getWorkgroups() {
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroups_show'),
        cache: true,
        success: function(data){
            $('#workgroups').html(data);

            $('#workgroups .workgroup .controls ul li:nth-child(1) a').click(function (){
                var workgroupId = $(this).closest('.workgroup').data('id');
                var workgroupSlug = $(this).closest('.workgroup').data('slug');
                var workgroup = $(this).closest('.workgroup');

                updateWorkgroup(workgroupId, workgroup);
            });

            $('#workgroups .workgroup .controls ul li:nth-child(2) a').click(function (){
                var workgroupId = $(this).closest('.workgroup').data('id');
                var workgroupSlug = $(this).closest('.workgroup').data('slug');
                var workgroup = $(this).closest('.workgroup');

                deleteWorkgroup(workgroupId, workgroup);
            });

            $('#workgroups .workgroup').each(function () {

                getProjectsByWorkgroup($(this).data('id'), $(this).data('slug'));
            });
        }
    });
}

// Display a form to create a workgroup
function createWorkgroup() {
    // Get the form to create the workgroup
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroup_create'),
        cache: true,
        success: function(data){
            $('#workgroup-create').append(data);

            // Hide the button and display the form
            $('#workgroup-create a.btn').click(function () {
                $('#workgroup-form-create').show();
                $('#workgroup-create a.btn').hide();
            });

            $('#workgroup-form-create input:reset').click(function (){
                $('#workgroup-create a.btn').show();
                $('#workgroup-form-create').hide();
            });

            // Send the workgroup
            $('#workgroup-form-create').submit(function (){

                $('#workgroup-create a.btn').show();
                $('#workgroup-form-create').hide();

                // Create the workgroup
                $.ajax({
                    type: "POST",
                    url: Routing.generate('BtaskBoardBundle_workgroup_create'),
                    data: $(this).serialize(),
                    cache: false,
                    success: function(data, textStatus, xhr){
                        // Refresh all workgroups
                        getWorkgroups();
                    }
                });

                return false;
            });
        }
    });
}

// Display a form to edit a workgroup
function updateWorkgroup(workgroupId, workgroup) {

    // Get the form
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroup_update', { id: workgroupId }),
        cache: false,
        success: function(data, textStatus, xhr){
            workgroup.children('.header').children().remove();
            workgroup.children('.header').append(data);

            $('#workgroup-form-edit-' + workgroupId + ' input[type=reset].btn').click(function (){
                // Refresh all workgroups
                getWorkgroups();
            });

            $('#workgroup-form-edit-' + workgroupId).submit(function (){

                $.ajax({
                    type: "POST",
                    url: Routing.generate('BtaskBoardBundle_workgroup_update', { id: workgroupId }),
                    data: $(this).serialize(),
                    cache: false,
                    success: function(data, textStatus, xhr){
                        // Refresh all workgroups
                        getWorkgroups();
                    }
                });

                return false;
            });
        }
    });
}

// Delete a workgroup
function deleteWorkgroup(workgroupId, workgroup)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('BtaskBoardBundle_workgroup_delete', { id: workgroupId }),
        cache: false,
        success: function(data, textStatus, xhr){
            // Remove the current workgroup
            $(workgroup).remove();
        }
    });
}

// Display all projects of a workgroup
function getProjectsByWorkgroup(workgroupId, workgroupSlug) {
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_projects_by_workgroup_show', { workgroup_slug: workgroupSlug }),
        cache: true,
        success: function(data){
            $('#workgroup-' + workgroupId + ' .projects').html(data);
        }
    });
}

function deleteProject(projectId) {
    $.ajax({
        type: "POST",
        url: Routing.generate('BtaskBoardBundle_project_delete', { id: projectId }),
        cache: false,
        success: function(data, textStatus, xhr){

            // Refresh all workgroups
            if (xhr.status == 200) {
                getWorkgroups();
            }
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
