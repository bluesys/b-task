this.prepareWorkgroupEdition = function( $data, $init ){

    $resetBt = $data.find('form input[type="reset"]')
    $saveBt = $data.find('form input[type="submit"]')

    $resetBt.click( function( e ) {

        if( $init ) {
            $data.replaceWith( $init );
            prepareWorkgroup( $init );
        }
        else {
            $data.remove();

        }

    })

    $saveBt.click( function( e ) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $data.find('form').attr('action'),
            data: $data.find('form').serialize(),
            success: function( data ){
                var $ndata = $(data)

                $data.replaceWith( $ndata );

                prepareWorkgroup( $ndata );
                setProjects( $ndata );
            }
        })
    });


}


this.prepareProjectEdition = function( $data, $init ){





    $resetBt = $data.find('input[type="reset"]')
    $saveBt = $data.find('input[type="submit"]')

    $resetBt.click( function( e ) {

        if( $init ) {
            $data.replaceWith( $init );
            prepareProject( $init );
        }
        else {
            $data.remove();

        }

    })

    $saveBt.click( function( e ) {

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $data.attr('action'),
            data: $data.serialize(),
            success: function( data ){
                setNavigation();
            }
        })
    });


}


this.prepareWorkgroup = function( $e ){



    // prepare edit
    $e.find('a').click( function( e ){

        if( $(this).attr('href') == '#' ) return;

        e.preventDefault();

        var $this = $(this);

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function( data ){
                if( $this.hasClass('edit')){

                    var $data = $(data)
                    var $init = $e;

                    $e.replaceWith( $data );
                    prepareWorkgroupEdition( $data, $init );


                }
                else if( $this.hasClass('addProject')  ){
                    var $data = $(data);
                    $e.show();
                    $e.find('div.projects').append( $data )
                    prepareProjectEdition($data, false);


                }
                else if( $this.hasClass('remove') ){
                    $e.remove();
                }
            }
        });
    })

    return $e;

}


this.prepareProject = function( $e ){
    // prepare edit
    $e.click( function( e ){
        e.preventDefault();
        $(this).addClass('on');
        initView( $('#content'), Routing.generate('BtaskBoardBundle_project', { 'project_slug' : $e.data('slug') }), function(){
            setTasks();
        });


    })


    return $e;
}


this.setProjects = function( $myWorkgroup ){
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_projects_by_workgroup_show', {'workgroup_slug': $myWorkgroup.data('slug')}),
        success: function( data ){
            $.each( data, function( i, e){
                var $myProject = prepareProject( $(e) );
                $myWorkgroup.find('div.projects').append( $myProject )
            })
        }
    })
}


this.setNavigation = function(){

    var $ct = $('#navigation .myworkgroups');
    console.log($ct)
    if( !$ct.length ){
        return false;
    }

    $ct.html('');
    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroups_show'),
        success: function(data){

            $.each( data, function( i, e){
                var $myWorkgroup = prepareWorkgroup( $(e) );
                setProjects( $myWorkgroup );
                $ct.append( $myWorkgroup )
            })

            $.ajax({
                type: "GET",
                url: Routing.generate('BtaskBoardBundle_projects_unassigned_show' ),
                success: function( data ){
                    $.each( data, function( i, e){
                        var $myProject = prepareProject( $(e) );
                        $('#workgroup-shared div.projects').append( $myProject )
                    });
                }
            });
        }
    });

}


this.prepareWorkgroupAdd = function(){

    $('#addWorkgroup').click( function( e ){

        e.preventDefault();

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function(data){
                var $data = $(data);
                $('#navigation .myworkgroups').append( $data )

                prepareWorkgroupEdition($data, false);


            }
        })
    })
}


$(function(){
    // init navigation
    setNavigation();
    prepareWorkgroupAdd()

})