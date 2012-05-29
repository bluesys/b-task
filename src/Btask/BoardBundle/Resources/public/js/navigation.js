/**
 * Import navigation element
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 *
 */

// Display all workgroups of the logged user
function getWorkgroups() {
    $.getJSON(Routing.generate('BtaskBoardBundle_workgroups_show'), function(data) {

        $.each(data, function(key, workgroup) {
            var $workgroup = $('#navigation #workgroups').append(workgroup);

            $workgroup.children('.controls ul li:nth-child(1) a').click(function (){
                alert('as');
            });
        });

    });
    /*
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroups_show'),
        cache: true,
        success: function(data){
            $('#navigation #workgroups').html(data);

            // Display a form to edit the current workgroup
            $('#workgroups .workgroup .controls ul li:nth-child(1) a').click(function (){

                var workgroupId = $(this).closest('.workgroup').data('id');
                var parent = $(this).closest('.header');
                getWorkgroupUpdateForm(workgroupId, parent);
            });

            // Display a form to delete the current workgroup
            $('#workgroups .workgroup .controls ul li:nth-child(2) a').click(function (){
                var workgroupId = $(this).closest('.workgroup').data('id');
                deleteWorkgroup(workgroupId);
            });
        }
    });
*/
}

// Update a workgroup
function updateWorkgroup(form, workgroupId)
{
    var $form = form;

    $.ajax({
        type: "POST",
        url: Routing.generate('BtaskBoardBundle_workgroup_update', { id: workgroupId }),
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

// Display a form to edit a workgroup
function getWorkgroupUpdateForm(id, parent) {
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroup_update', { id: id }),
        cache: false,
        success: function(data, textStatus, xhr){
            $(parent).append(data);

            // Hide the title, controls button and show the form
            $(parent).children().hide();
            $('#workgroup-form-edit-' + id).show();

            $('#workgroup-form-edit-' + id).submit(function (){
                $(parent).children().show();
                $(this).hide();
                updateWorkgroup($(this), id);

                return false;
            });
        }
    });
}

function createWorkgroup(form)
{
    var $form = form;

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

                // Hide the form and display the button
                $form.hide();
                $('#workgroup-create a.btn').show();

                // Create the workgroup
                createWorkgroup($(this));

                return false;
            });
        }
    });
}

function deleteWorkgroup(workgroupId)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('BtaskBoardBundle_workgroup_delete', { id: workgroupId }),
        cache: false,
        success: function(data, textStatus, xhr){

            // Refresh all workgroups
            if (xhr.status == 200) {
                getWorkgroups();
            }
        }
    });
}

function getProjects(workgroupId)
{
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_projects_by_workgroup'),
        cache: true,
        success: function(data){

            // Clear the workgroup
            $("#workgroup-" + workgroupId + ' .projects').html('');

            // Display projects
            $.each(data, function(project) {
                $("#workgroup-" + workgroupId + ' .projects').append(project);
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
