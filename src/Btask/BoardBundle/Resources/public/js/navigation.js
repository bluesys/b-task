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
                    prepareProject($data, false);


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

    var $ct = $('#navigation');
    if( !$ct.length ){
        return false;
    }

    $.ajax({
        type: "GET",
        url: Routing.generate('BtaskBoardBundle_workgroups_show'),
        success: function(data){

            $.each( data, function( i, e){
                var $myWorkgroup = prepareWorkgroup( $(e) );
                setProjects( $myWorkgroup );
                $ct.append( $myWorkgroup )
            })
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
                $('#navigation').append( $data )

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