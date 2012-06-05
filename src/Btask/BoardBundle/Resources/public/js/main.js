this.initView = function($e, view, callback){

	$.ajax({
        type: "GET",
        url: view,
        success: function( data ){
            $e.html(data)
            if( callback ) callback();
        }
    })




}


$(function(){

})