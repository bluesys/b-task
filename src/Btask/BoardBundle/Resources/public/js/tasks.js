this.prepareTaskEdition = function( $data, $init ){

    $resetBt = $data.find('form input[type="reset"]')
    $saveBt = $data.find('form input[type="submit"]')

    $resetBt.click( function( e ) {
        e.preventDefault();
        $data.replaceWith( $init )
    })

    $saveBt.click( function( e ) {

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $data.find('form').attr('action'),
            data: $data.find('form').serialize(),
            success: function( data ){
                setTasks();
            }
        })
    });
}



this.prepareTask = function( $e, project ){
   // prepare edit
    $e.find('a').click( function( e ){
        if( $(this).attr('href') == '#') return;

        e.preventDefault();
        var $this = $(this);

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function( data ){
                var $data = $(data);

                if( $this.hasClass('edit')){

                	var $init = $e;
                    $e.replaceWith( $data );
                    prepareTaskEdition( $data , $init );


                }
                else{
                   setTasks();
                   return;
                }
            }
        });
    })

    return $e;

}


this.getTaskUrl = function ( project, user, st){

    var params = new Object();
    if( user ) params.user = user
    if( st ) params.state = st

    if( project ) {
        params.project_slug = project;
        url = Routing.generate('BtaskBoardBundle_tasks_by_project_show', params);

    }
    else {
        url = Routing.generate('BtaskBoardBundle_tasks_by_state_show', params);
    }

    return url
}

this.setTasks = function( ){

    $('#tasks div.list' ).each( function( k, e ){
        $e = $(e)

        var project     = $('#navigation').find('.project.on').data('slug');
        var state       = $e.parent().parent().attr('id');
        var user        = $('#users').val();

        url = getTaskUrl( project, user, state )

        $.ajax({
            type: "GET",
            async: false,
            url: url,
            success: function( data ){
                $e.html('')
                $.each( data, function( i, e ){
                    var $mytask = prepareTask( $(e) );
                    $e.append( $mytask )
                })
            },
            error: function(){
                $e.html('')
            }
        })

    })

}


$(function(){

	initView( $('#content'), Routing.generate('BtaskBoardBundle_today'), function(){
           setTasks();
    });
})