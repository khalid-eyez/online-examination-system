<?php
use yii\bootstrap4\Breadcrumbs;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->params['courseTitle']='<i class="fas fa-user-secret nav-icon"></i> Admins';
$this->title = 'Admins';
$this->params['breadcrumbs'] = [
    ['label'=>$this->title]
];
?>
<div class="site-index">

    

    <div class="body-content">
            <!-- Content Wrapper. Contains page content -->
   
       <div class="container-fluid">
      
 <div class="row">
          <!-- Left col -->
          <section class="col-lg-12">
            <!-- Custom tabs (Charts with tabs)-->
           
           
                <a href="#" class="btn btn-default btn-sm float-right m-0 col-xs-12" data-toggle="modal" data-target="#adminmodal"><i class="fas fa-user-plus"></i> Add Admin</a>
              
              <!-- /.card-header -->
            
            <table class="table table-bordered table-striped table-hover" id="AdminTable" style="width:100%; font-size:11.5px;">
            <thead>
            <tr><th width="1%">#</th><th>Full Name</th><th>Email</th><th>Phone Number</th><th>College</th><th width="10%">Action</th></tr>
            
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php foreach($users as $user): ?>
              <?php if($user->user->role->item_name=='SUPER_ADMIN'){continue;} ?>
            <tr class="<?=($user->user!=null && $user->user->isLocked())?"text-danger":""?>">
            <td><?= ++$i; ?></td>
            <td><?= $user->full_name?></td>
            <td><?= $user->email?></td>
            <td><?= $user->phone?></td>
            <td><?= $user->college->college_abbrev ?></td>
            <td>
            <a href="<?=Url::to(['/users/update','admin'=>urlencode(base64_encode($user->adminID))])?>" class=" m-0" data-toggle="tooltip" data-title="Update Admin"><i class="fas fa-edit"></i></a> 
            <a href="<?=Url::to(['/users/reset','id'=>urlencode(base64_encode($user->userID))])?>"  data-toggle="tooltip" data-title="Reset Admin Password" class="mr-1"><i class="fa fa-refresh"></i></a> 
            <?php
            if($user->user!=null && $user->user->isLocked())
            {
            ?>
            <a href="<?=Url::to(['/users/unlock','id'=>urlencode(base64_encode($user->userID))])?>"  data-toggle="tooltip" data-title="Reactivate/Unlock Admin" class="mr-1"><i class="fa fa-unlock"></i></a>  
            <?php
            }
            else
            {
            ?>
            <a href="<?=Url::to(['/users/lock','id'=>urlencode(base64_encode($user->userID))])?>"  data-toggle="tooltip" data-title="Lock Admin" class="mr-1"><i class="fas fa-user-lock"></i></a>
            <?php
            }
            ?>
            
            <a href="#"  id=<?=$user->userID?> data-toggle="tooltip" data-title="Delete Admin" class="mr-1  userdel"><i class="fa fa-trash"></i></a> 
            </td>
            </tr>
            <?php endforeach ?>
            </tbody>
            </table>
             
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
         
          <!-- right col -->
          <?=$this->render("newadmin")?>

    </div>
</div>
<?php 
$script = <<<JS
$(document).ready(function(){
  $("#AdminTable").DataTable({
    responsive:true
  });
  // alert("JS IS OKEY")

  $(document).on('click', '.userdel', function(){
      var user = $(this).attr('id');
      Swal.fire({
  title: 'Delete User?',
  text: "You won't be able to revert to this, and the user will not be able to recover his account. consider locking the user instead, if this decision is for temporary reasons !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/users/delete',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{userid:user},
      success:function(data){
        if(data.deleted){
          Swal.fire(
              'Deleted !',
              data.deleted,
              'success'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
   

        }
        else
        {
          Swal.fire(
              'Deleting failed !',
              data.failure,
              'error'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
        }
      }
    })
   
  }
})

})
});
JS;
$this->registerJs($script);
?>
