<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\ClassRoomSecurity;
use frontend\assets\AppAsset;

AppAsset::register($this);
/* @var $this yii\web\View */
$cid=yii::$app->session->get('ccode');
$this->params['courseTitle'] ="<i class='fa fa-pen'></i> Quiz Scores";
$this->title ="Quiz Scores";
$this->params['breadcrumbs'] = [
  ['label'=>$cid.' Quizes', 'url'=>Url::to(['class-quizes','cid'=>ClassRoomSecurity::encrypt(yii::$app->session->get("ccode"))])],
  ['label'=>$this->title]
];

?>
 

<div class="site-index">
    <div class="body-content ">
            <!-- Content Wrapper. Contains page content -->
   
       <div class="container-fluid table-responsive">
            
            <table  class="table table-striped table-bordered table-hover text-sm " id="studenttable" style="width:100%">
            <a href="<?=Url::to(['quiz-manual-marking','quiz'=>ClassRoomSecurity::encrypt($quiz)])?>" class="btn btn-default btn-sm p-1 pl-3 pr-3 text-primary float-right ml-1"><i class="fa fa-pen"></i> Mark</a>
		<thead>
			<tr>
       <th>
       S/no
				</th>
				<th>
				Registration Number
				</th>
        <th>
			   Markables
				</th>
        <th>
			   Status
				</th>
        <th>
			   Score
				</th>
        <th>
			
				</th>
			</tr>
		</thead>
		<tbody>
								<?php foreach($scores as $index=>$score){?>
                  
                    <tr id=<?=$score->reg_no?> >
                    <td><?=$index+1?></td>
									 	<td><?=Html::encode($score->reg_no);?></td>
                     <td><?=$score->markables?></td>
                     <td><?=Html::encode($score->status); ?></td>
                    <td><?=Html::encode($score->score); ?></td>
                   
                    <td>
                      <a href="<?=Url::to(['edit-score','scoreID'=>$score->SQ_ID])?>" data-toggle="tooltip" data-title="Update Score"><i class="fa fa-edit"></i></a>
                      <a href="<?=Url::to(['get-student-vfps','student'=>base64_encode($score->regNo->userID),'assessment'=>base64_encode($score->quizID)])?>" data-toggle="tooltip" data-title="View student VFPs" class="ml-1 mr-1"><i class="fa fa-paw"></i></a>
                      <?php
                      if($score->status=="submitted" || $score->status=="marked")
                      {
                      ?>
                      <a href="<?=Url::to(['student-submits-pdf','student'=>base64_encode($score->regNo->userID),'assessment'=>base64_encode($score->quizID)])?>" data-toggle="tooltip" data-title="Download Student Submission" class="ml-1 mr-1"><i class="fas fa-download"></i></a>
                      <?php
                      }
                      ?>
                      <a href="#" class="text-danger scoredel" id=<?=$score->SQ_ID?> data-toggle="tooltip" data-title="Delete Record"><i class="fa fa-trash text-danger scoredel" ></i></a>
                    </td>
                    
                    
						 			</tr>
						 		
									 <?php } ?>
		
			

		</tbody>
		</table>
         
    </div>
    </div>
</div>



      </div><!--/. container-fluid -->

    </div>
</div>
</div>

                
    



<?php 
$script = <<<JS
$(document).ready(function(){
  $('#assignstudents').select2();
  $('#remstudents').select2();
  $(".headcard").on('show.bs.collapse','.collapse', function(e) {
  $(e.target).parent().addClass('shadow');
  });
  $(".headcard").on('hidden.bs.collapse','.collapse', function(e) {
  $(e.target).parent().removeClass('shadow');
  });
  $("#CoursesTable").DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                title: 'Students\' Assessment Scores'
            },
            {
                extend: 'excelHtml5',
                title: 'Students\' Assessment Scores'
            },
            'print',
        ]
  });
  //$("#studenttable").DataTable({
    //responsive:true,
  //});
  
  $('#studenttable').DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                title: 'Students\' Quiz Scores'
            },
            {
                extend: 'excelHtml5',
                title: 'Students\' Quiz Scores'
            },
            'print',
        ]
  } );
$('body').on('click','.scoredel',function(){

 //w.preventDefault();
var score=$(this).attr('id');
  Swal.fire({
  text: "Delete This Record",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, Delete!'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'delete-score',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{score:score},
      success:function(data){
        if(data.message){
          Swal.fire(
              '',
              data.message,
              'success'
    )
    setTimeout(function(){
      window.location.reload();
    },100);
   

        }
        else
        {
          Swal.fire(
              '',
              data.failure,
              'success'
    )
         setTimeout(function(){
        window.location.reload();
        },100);
   
        }
      }
    })
   
  }
})
})
})

JS;

$this->registerJs($script);

?>
