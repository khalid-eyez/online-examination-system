<?php
use yii\bootstrap4\Breadcrumbs;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
$this->params['courseTitle']="<i class='fa fa-user-graduate'></i> Students";
$this->params['breadcrumbs'][] = ['label' => 'Students'];
$this->title = 'Students';
?>
<div class="site-index">

    

    <div class="body-content">
            <!-- Content Wrapper. Contains page content -->
   
       <div class="container-fluid">
      
 <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 table-responsive">
            <!-- Custom tabs (Charts with tabs)-->
       
          
            
            <table class="table table-bordered table-striped table-hover" id="StudentList" style="width:100%;font-size:11.5px;">
            <thead>
            <tr><th width="1%">#</th><th>Full Name</th><th>Reg#</th><th>E-mail</th><th>Program</th><th>YOS</th><th>Department</th><th width="5%">College</th></tr>
            
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php foreach($students as $std): ?>
            <?php 
            if($std->program->department->college->collegeID!=4 || $std->YOS!=3)
            {
              continue;
            }
              ?>
            <tr class="<?=($std->user!=null && $std->user->isLocked())?"text-danger":""?>">
            <td><?= ++$i; ?></td>
            <td><?= ucwords(strtolower($std->fullName)) ?></td>
            <td><?= $std->reg_no ?></td>
            <td><?= $std->email ?></td>
            <td><?= $std->programCode ?></td>
            <td><?= $std->YOS ?></td>
            <td><?= $std->program->department->depart_abbrev ?></td>
            <td><?= $std->program->department->college->college_abbrev ?></td>
            
            
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
     

      </div><!--/. container-fluid -->

    </div>
</div>
<?php 
$script = <<<JS
$(document).ready(function(){
  $("#StudentList").DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                title: 'Class students list'
            },
            {
                extend: 'excelHtml5',
                title: 'Class students list'
            },
            'print',
        ]
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
      url:'/studentmanage/delete',
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
