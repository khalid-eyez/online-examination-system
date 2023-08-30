

$(function(){

  //click event of the add carry button
 $('#modal_button').click(function(){
   $('#modal').modal('show')
              .find('#modal_content')
              .load($(this).attr('value'));
 });
});

$(function(){

//click event to view announcent
$('#modal_button2').click(function(){
  $('#modal2').modal('show')
             .find('#modal_content2')
             .load($(this).attr('value'));
});
});


$(function(){

    //click event of the add carry button
    $('#group_modal_button').click(function(){
        $('#group_modal').modal('show')
            .find('#group_modal_content')
            .load($(this).attr('value'));
    });
});



/**
 * sweet alert for delete carry
 */

$(document).on('click', '.btn-delete', function(){
 
  var carry_id = $(this).attr('carry_id');

  Swal.fire({
title: '<small>Are you sure?</small>',
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Delete'
}).then((result) => {
if (result.isConfirmed) {

$.ajax({
  url: '/student/delete_carry' ,
  method:'post',
  async:true,
  cache: false,
  dataType:'JSON',
  data:{carry_id:carry_id},
  success:function(data){
    if(data.message){
      Swal.fire(
          'Deleted!',
          data.message,
          'success'
)
setTimeout(function(){
  window.location.reload();
},20);


    }
  }
})

}
})

});


/**
 * sweet alert for forum question delete
 */

$(document).on('click', '.btn-qn-delete', function(){

    var question_id = $(this).attr('forum_qn_id');

    Swal.fire({
        title: '<small>Are you sure?</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: '/forum/delete-forum-qn' ,
                method:'post',
                async:true,
                cache: false,
                dataType:'JSON',
                data:{question_id:question_id},
                success:function(data){
                    if(data.message){
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        )
                        setTimeout(function(){
                            window.location.reload();
                        },20);


                    }
                }
            })

        }
    })

});



/**
 * sweet alert for delete carry
 */

$(document).on('click', '.btn-delete-group', function(){

    var groupID = $(this).attr('groupID');

    Swal.fire({
        title: '<small>Are you sure?</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: '/student/delete-group' ,
                method:'post',
                async:true,
                cache: false,
                dataType:'JSON',
                data:{groupID:groupID},
                success:function(data){
                    if(data.message){
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        )
                        setTimeout(function(){
                            window.location.reload();
                        },20);


                    }
                }
            })

        }
    })

});

//exiting group

$(document).on('click', '.exitgroup', function(){

    var groupID = $(this).attr('groupID');

    Swal.fire({
        title: '<small>Are you sure?</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Exit'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: '/student/exit-group' ,
                method:'post',
                async:true,
                cache: false,
                dataType:'JSON',
                data:{groupID:groupID},
                success:function(data){
                    if(data.message){
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        )
                        setTimeout(function(){
                            window.location.reload();
                        },20);


                    }
                }
            })

        }
    })

});



//submit by drug file
 const activateTag = document.querySelector('.drop-over');
 const  dragText = document.querySelector('.drag-header');
 const  documentFile = document.querySelector('#document-file')




$(function(){

    $('#document-file').change(ev => {

        $(ev.target).closest('form').trigger('submit');
    })
});











