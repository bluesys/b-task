/*
 * Dashboard effects
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 */

// Get all the post-it and display them
function getAllPostIt(url) {
	$.ajax({
	    type: "GET",
	    url: url,
	    cache: false,
	    success: function(data){
	        $('#post-it').append(data);

	        // Display the form for task edition
			$('.post-it .btn.edit').click(function () {

				var itemId = $(this).parent().data('id');

				if( $('#panel #rdb-note-type').is(':checked') ) {
					getNoteForm(itemId);
				}
				else {
					getTaskForm(itemId);
				}

				$('#panel-item #rdb-task-type').click( function(){
					if( $(this).is(':checked') ) getTaskForm(itemId);
				});

				$('#panel-item #rdb-note-type').click( function(){
					if( $(this).is(':checked') ) getNoteForm(itemId);
				});

				$('#panel-item').show();
			});
	    }
	});
}

// Get overdue tasks and display them
function getOverdueTasks(url) {
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function(data){
            $('#overdue-tasks').append(data);
        }
    });
}

// Get tasks to be done and display them
function getPlannedTasks(url) {
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function(data){
            $('#planned-tasks').append(data);
        }
    });
}

// Get done tasks and display them
function getDoneTasks(url) {
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function(data){
            $('#done-tasks').append(data);
        }
    });	
}

// Display the form to edit a task
// TODO: Put route in place of hardcoded URL
function getTaskForm(taskId) {
    $.ajax({
        type: "GET",
        url: "task/" + taskId + "/edit",
        cache: true,
        success: function(data){
            $('#panel-item #options').html(data);

            // Display the datepicker on due date and planned date input
            $('#option-due .controls input').datepicker();
            $('#option-planned .controls input').datepicker();
        }
    });
}

function getNoteForm(noteId) {
    $.ajax({
        type: "GET",
        url: "note/" + noteId + "/edit",
        cache: true,
        success: function(data){
            $('#panel-item #options').html(data);
        }
    });
}
/*
// Show the form to create a post-it in dashboard
$('.post-it.add').click(function () {
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
*/
