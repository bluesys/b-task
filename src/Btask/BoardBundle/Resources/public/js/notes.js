this.prepareNoteEdition = function( $data, $init ){
    $data.find('fieldset').hide();
    $data.find('fieldset.note').show();

    $data.find("#fplanned input").change(function(){
        if( $(this).val() != '' ){
            $("#ftype select option").each(function(){
                if ($(this).text() == "Task")
                    $(this).attr("selected","selected");
            });
        }
        else {
            $("#ftype select option").each(function(){
                if ($(this).text() == "Post-it")
                    $(this).attr("selected","selected");
            });
        }
    });

    // Change the type of the item on tab click
    $data.find('.tabs a').click(function( e ){
        e.preventDefault()
        $(this).parent().find('a').removeClass('active')
        $(this).addClass('active')


        if( $('.tabs a.active').attr('href') == 'note' ){
            $data.find('fieldset').hide();
            $data.find('fieldset.note').show();
            $("#ftype select option").each(function(){
                if ($(this).text() == "Note")
                    $(this).attr("selected","selected");
            });
        }
        else if( $('.tabs a.active').attr('href') == 'task' ){
            $data.find('fieldset').hide();
            $data.find('fieldset.task').show();
            if( $("#fplanned input").val() != '' ){
                $("#ftype select option").each(function(){
                    if ($(this).text() == "Task")
                        $(this).attr("selected","selected");
                });
            }
            else {
                $("#ftype select option").each(function(){
                    if ($(this).text() == "Post-it")
                        $(this).attr("selected","selected");
                });
            }
        }


    })


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
        prepareNote( $data );

    })

    $saveBt.click( function( e ) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $data.find('form').attr('action'),
            data: $data.find('form').serialize(),
            success: function( data ){
                var $note = $(data)

                $data.replaceWith( $note );
                prepareNote( $note );

            }
        })
    });
}



this.prepareNote = function( $e, project ){

    // prepare edit
    $e.find('a.action').click( function( e ){

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
                    prepareNoteEdition( $data , $init );

                }
            }
        });
    })

    return $e;

}


this.getNoteUrl = function ( project, user){

    var params = new Object();

    if( user ) params.user = user

    if( project ) {
        params.project_slug = project;
        url = Routing.generate('BtaskBoardBundle_notes_by_project_show', params);

    }
    else {
        url = Routing.generate('BtaskBoardBundle_notes_show', params);
    }

    return url
}

this.setNotes = function( ){

    var project     = $('#navigation').find('.project.on').data('slug');
    var user        = $('#users').val();


    $e = $('#notes')
    $e.html('');

	url = getNoteUrl( project, user )
    $.ajax({
        type: "GET",
        async: false,
        url: url,
        success: function( data ){
            $e.html('')
            $.each( data, function( i, e ){
                var $mynote = prepareNote( $(e) );
                    $e.append( $mynote )
                })
            },
            error: function(){
                $e.html('')
            }
        })

}