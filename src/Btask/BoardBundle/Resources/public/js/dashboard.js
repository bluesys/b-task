this.preparePostitEdition = function( $data, $init ){
    // Change the type of the item
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
            preparePostit( $init );
        }
        else {
            $data.remove()
        }


    })

    $saveBt.click( function( e ) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $data.find('form').attr('action'),
            data: $data.find('form').serialize(),
            success: function( data ){
                var $postit = $(data)
                $data.replaceWith( $postit );
                preparePostit( $postit );
                setTasks();
                setNotes();
            }
        })
    });
}


this.preparePostit = function( $e ){
    // prepare edit
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
