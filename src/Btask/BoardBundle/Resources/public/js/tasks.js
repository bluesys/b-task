
this.prepareTask = function( $e ){
   // prepare edit
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
                        url: Routing.generate('BtaskBoardBundle_task_close', {'id': $e.data('id')}),
                        success: function( data ){
                            setTasks( $('#overdue-tasks'), Routing.generate('BtaskBoardBundle_tasks_by_state_show', {'state': 'overdue'}) )
            				setTasks( $('#planned-tasks'), Routing.generate('BtaskBoardBundle_tasks_by_state_show', {'state': 'planned'}) )
            				setTasks( $('#done-tasks'), Routing.generate('BtaskBoardBundle_tasks_by_state_show', {'state': 'done'}) )
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


this.setTasks = function( $e, url ){
	$.ajax({
        type: "GET",
        url: url,
        success: function( data ){
        	$e.html('')
            $.each( data, function( i, e){
                var $mytask = prepareTask( $(e) );
                $e.append( $mytask )
            })
        },
        error: function(){

        	$e.html('')
        }
    })
}




$(function(){

	initView( $('#content'), Routing.generate('BtaskBoardBundle_today'), function(){
            setTasks( $('#overdue-tasks'), Routing.generate('BtaskBoardBundle_tasks_by_state_show', {'state': 'overdue'}) )
            setTasks( $('#planned-tasks'), Routing.generate('BtaskBoardBundle_tasks_by_state_show', {'state': 'planned'}) )
            setTasks( $('#done-tasks'), Routing.generate('BtaskBoardBundle_tasks_by_state_show', {'state': 'done'}) )
    });
})