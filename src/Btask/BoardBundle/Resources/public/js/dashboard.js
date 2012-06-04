
this.preparePostit = function( $e ){
	$e.find('a').click( function( e ){

        e.preventDefault();

        var $this = $(this);

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function( data ){
                if( $this.hasClass('close')){

                	$.ajax({
                        type: "POST",
                        url: Routing.generate('BtaskBoardBundle_postit_close', {'id': $e.data('id')}),
                        success: function( data ){
                        	if( project ){
                        		setTask4Project( $e );
                        	}
                        	else {
                        		setTask4Today( $e );
                        	}

                        }
                    })


                }
                else if( $this.hasClass('remove') ){
                    var $data = $(data)
                    $.ajax({
                        type: "POST",
                        url: $data.attr('action'),
                        data: $data.serialize(),
                        success: function( data ){
                            e.preventDefault();

                            var $data = $(data)

                            $e.replaceWith( $data );
                            prepareWorkgroup( $data );

                        }
                    })
                    $e.remove();
                }
            }
        });
    })

    return $e;
}
this.preparePostitAdd = function(){

	$('.post-it.add').click( function( e ){

		e.preventDefault();
		$.ajax({
        	type: "GET",
        	url: Routing.generate('BtaskBoardBundle_post_it_create'),
        	success: function( data ){
        		var $data = $('<div class="post-it alert" data-id="0"></div>');
        		preparePostit($data);
    			$data.append( $(data ));
        		$('#post-it').prepend( $data )

            }
        })
    })
}

this.setPostits = function( ){

	$.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_posts_it_show'),
        success: function( data ){
        	$('#post-it').html('')
            $.each( data, function( i, e){
                var $mypostit = preparePostit( $(e) );
                $('#post-it').append( $mypostit )
            })
        }
    })



}


$(function(){
	setPostits()
	preparePostitAdd();
})

