<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\admin\models\Studentsearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Students';
?>
<div class="student-index text-xs">

    <?php Pjax::begin(); ?>
    <?php

    $gridcolumns = [
      'reg_no',
      'fname',
      'mname',
      'lname',
      'email:email',
      'gender',

      [
          'class' => 'yii\grid\ActionColumn',
          'template' => '{update}{reset}{block}{delete_stud}',  // the default buttons + your custom button
          'buttons' => [
              'update' => function($url, $model, $key) { 
                  
                  return Html::a('<i class="fas fa-edit text-success"></i>', ['studentmanage/update','id'=>urlencode(base64_encode($model->userID))], ['data-pjax' => '0','class'=>'ml-1','data-toggle'=>'tooltip','data-title'=>'Update User']);// render your custom button
                 
              },
              'reset' => function($url, $model, $key) { 
                  
                  return Html::a('<i class="fa fa-refresh text-success"></i>', ['studentmanage/reset','id'=> urlencode(base64_encode($model->userID))], ['data-pjax' => '0','class'=>'ml-1']);// render your custom button
                 
              },
              'block' => function($url, $model, $key) { 
                  if($model->user!=null && $model->user->isLocked() )
      {
     
      return '<a href="'.Url::to(['studentmanage/unlock','id'=>urlencode(base64_encode($model->userID))]).'"  data-toggle="tooltip" data-title="Reactivate/Unlock User" class="m-1"><i class="fa fa-unlock text-success"></i></a>';  
      
      }
      else
      {
    
      return '<a href="'.Url::to(['studentmanage/lock','id'=>urlencode(base64_encode($model->userID))]).'"  data-toggle="tooltip" data-title="Lock User" class="m-1"><i class="fas fa-user-lock text-success"></i></a>';
     
      }
                  
                 
              },
              'delete_stud' => function($url, $model, $key) { 
                  
                  return Html::a('<i class="fa fa-trash userdel"></i>',null,['class'=>'userdel text-danger','id'=>$model->reg_no]);
                 
              },
          ]
          ],
     
        ];
    ?>
    <?= ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridcolumns,
    'showConfirmAlert'=>false,
    'container'=>['class'=>'btn-group float-right mb-2', 'role'=>'group'],
    'dropdownOptions'=>[
      'icon'=>"<i class='fas fa-file-export'></i>",
      'label'=>"Export List",
      'class' => 'btn btn-outline-secondary btn-default'
    ],
    'columnSelectorOptions'=>[
      'icon'=>"<i class='fa fa-list'></i>",
      'label'=>'Select Columns'
    ],
    'timeout'=>240,
    'fontAwesome'=>true,
    'exportConfig'=>[
      'Html'=>false,
      'Txt'=>false,
      'Xls'=>false,
      'Xlsx'=>[
        'label' =>'Excel',
        'icon'=>'fa fa-file-excel-o ml-2'
      ],
      'Pdf'=>[
        'icon'=>'fa fa-file-pdf-o ml-2'
      ],
      'Csv'=>[
        'icon'=>'fa fa-file ml-2'
      ]
      
      ],
      'filename'=>'Students'
])."\n"; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' =>$gridcolumns 
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php 
$script = <<<JS
$(document).ready(function(){
  $("#StudentList").DataTable({
    responsive:true
  });
  // alert("JS IS OKEY")

  $(document).on('click', '.userdel', function(){
      var user = $(this).attr('id');
      //alert(user);
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
      url:'/admin/studentmanage/delete',
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
$('body').addClass("sidebar-collapse");
//$(body).addClass("sidebar-mini");
});
JS;
$this->registerJs($script);
?>
