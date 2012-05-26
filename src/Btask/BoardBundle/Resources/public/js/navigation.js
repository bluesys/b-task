/**
 * Import navigation element
 *
 * @author geoffroy.perriard@gmail.com
 *
 */

function getWorkgroups(url) {
    $.ajax({
        type: "GET",
        url: url,
        cache: true,
        success: function(data){
            $('#navigation').html(data);

            // Get the heighter of the dashboard and the main navbar
            var heighter = $('#dashboard').outerHeight() + $('.navbar.navbar-fixed-top').outerHeight();

            $('#navigation').css({ top : heighter });
            $('#navigation').show();
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
