
this.preparePostit = function( $e ){

	return $e;

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
})

