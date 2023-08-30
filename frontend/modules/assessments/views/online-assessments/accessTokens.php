<?php
use frontend\models\ClassRoomSecurity;
use yii\helpers\Url;
use common\models\Quizaccesstokens;

$this->title = 'Quiz Access Tokens';

?>


<div class="site-index">

    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
     <div class="container-fluid table-responsive">
      <table class="table table-striped  table-hover text-sm" id="tokentable">
      <a href="#" quiz=<?=ClassRoomSecurity::decrypt($_GET['quiz'])?> class="btn btn-sm btn-default shadow border btn-rounded float-right mb-2 ml-1 alldel" data-toggle="tooltip" data-title="Delete All tokens & make this public"><i class="fa fa-trash text-danger"></i> Delete All</a>
      <a href="<?=Url::to(['get-tokens-pdf','quiz'=>$_GET["quiz"]])?>" class="btn btn-sm btn-default shadow border btn-rounded float-right mb-2 ml-1" data-toggle="tooltip" data-title="Download Distributable PDF Format"><i class="fa fa-file-pdf-o text-danger"></i> Download</a>
      <a href="#" class="btn btn-sm btn-default shadow border btn-rounded float-right mb-2 ml-1" data-target="#tokensmodal" data-toggle="modal"><i class="fa fa-cogs"></i> Generate Tokens</a>
      
        <thead>
        <tr><th>#</th><th>Token</th><th>Expire Date</th><th>Consumed by</th><th>Status</th><th></th></tr>
</thead>
<tbody>
      <?php
       $count=1;
       foreach($tokens as $index=>$token)
       {
        ?>
         <tr>
          <td><?=$count?></td>
          <td><?=strtoupper(base64_decode($token->token))?></td>
          <td><?=date_format(date_create($token->expires_on),"d-m-Y H:i:s")?></td>
          <td><?=$token->consumed_by?></td>
          <td>
            <?php
             if($token->isExpired())
             {
              ?>
              <span class="badge badge-danger">Expired</span>
              <?php
             }
             else if($token->isUsed())
             {
             ?>
             <span class="badge badge-danger">Used</span>
             <?php
             }
             else
             {
              ?>
              <span class="badge badge-success">Active</span>
              <?php
             }
            ?>
        </td>
        <td>
          <a href="#" id=<?=$token->tokenID?>  data-toggle="tooltip" data-title="Delete token" class="tdel"><i class="fa fa-trash text-danger" ></i></a>
        </td>
       </tr>
        <?php
        $count++;
       }
      ?>
      </tbody>
      </table>
     </div>
    
       

    </div>


    <?=$this->render('generatetokensmodal',['tokenizer'=>new Quizaccesstokens()])?>
</div>
     

<?php
$script = <<<JS
$(document).ready(function(){
$("#tokentable").DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                title: 'Tokens'
            },
            {
                extend: 'excelHtml5',
                title: 'Tokens'
            },
            'print',
        ]
  });
$(document).on('click', '.tdel', function(){
      var token = $(this).attr('id');
      Swal.fire({
  title: 'Delete Token ?',
  text: "You won't be able to revert to this! and deleted token will no longer be valid if already distributed !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete !'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'delete-token',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{token:token},
      success:function(data){
        if(data.success){
          Swal.fire(
              'Deleted !',
              data.success,
              'success'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
   

        }
        else
        {
          Swal.fire(
              'Deleted !',
              data.failure,
              'error'
    )
        }
      }
    })
   
  }
})

});

$(document).on('click', '.alldel', function(){
      var quiz = $(this).attr('quiz');
      Swal.fire({
  title: 'Delete All Tokens ?',
  text: "This will delete all tokens thus make this quiz accessible by students without any restrictions! and deleted tokens will no longer be valid if already distributed !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete All !'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'delete-all-tokens',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{quiz:quiz},
      success:function(data){
        if(data.success){
          Swal.fire(
              'Deleted !',
              data.success,
              'success'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
   

        }
        else
        {
          Swal.fire(
              'Deleted !',
              data.failure,
              'error'
    )
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
<?php 
$this->registerCssFile('@web/plugins/select2/css/select2.min.css');
$this->registerJsFile(
  '@web/plugins/select2/js/select2.full.js',
  ['depends' => 'yii\web\JqueryAsset']
);
$this->registerJsFile(
  '@web/js/create-assignment.js',
  ['depends' => 'yii\web\JqueryAsset'],

);



?>




