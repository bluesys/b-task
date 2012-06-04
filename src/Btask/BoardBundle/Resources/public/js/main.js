this.initView = function($e, view, callback){

	$.ajax({
        type: "GET",
        url: view,
        success: function( data ){

            $e.replaceWith(data)

            if( callback ) callback();
        }
    })




}


$(function(){

})