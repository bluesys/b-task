this.preparePostitEdition = function( $data, $init ){

    $resetBt = $data.find('form input[type="reset"]')
    $saveBt = $data.find('form input[type="submit"]')

    $resetBt.click( function( e ) {

        e.preventDefault();

    	if( $init ){
    		$data.replaceWith( $init )
    	}
    	else {
			$data.remove()
    	}

    })

    $saveBt.click( function( e ) {

        e.preventDefault();
        console.log($data.find('form').attr('action'));
        $.ajax({
            type: "POST",
            url: $data.find('form').attr('action'),
            data: $data.find('form').serialize(),
            success: function( data ){
                var $postit = $(data)
                $data.replaceWith( $postit );
                preparePostit( $postit );
            }
        })
    });
}


this.preparePostit = function( $e ){

	$e.find('a').click( function( e ){

        e.preventDefault();

        var $this = $(this);

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function( data ){

            	var $data = $(data)
                if( $this.hasClass('close')){
                    $e.remove()
                }
                else if( $this.hasClass('edit') ){
                    var $init = $e;
                    $e.replaceWith( $data );
                	preparePostitEdition( $data , $init );
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
        	success: function(data){
        		var $data = $(data);
        		$('#post-it').prepend( $data )

        		preparePostitEdition($data, false);


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

