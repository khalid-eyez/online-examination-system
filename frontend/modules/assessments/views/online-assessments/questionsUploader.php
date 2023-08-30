<?php

use common\models\Course;
use common\models\GroupAssignment;
use common\models\GroupGenerationAssignment;
use common\models\Groups;
use common\models\Student;
use common\models\StudentGroup;
use frontend\models\ClassRoomSecurity;
use frontend\models\GroupAssSubmit;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;



/* @var $this yii\web\View */
$cid=yii::$app->session->get('ccode');


?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
        <div class="modal fade" id="uploadermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header bg-success pt-2 pb-2">
        <span class="modal-title" id="exampleModalLabel"><h6><i class='fa fa-upload'></i> Upload Questions</h6></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
         <form action="questions-uploader" method="post" enctype="multipart/form-data">
           <input type="file" name="questionsfile" class="form-control" accept=".qb" required></input>
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

        <button type="submit" class="btn btn-default shadow text-success m-2 float-right" ><i class="fa fa-upload"></i> Upload</button>
        </form>
    </div>
</div>
</div>
</div>
</div>
    </div>
</div>
   
<?php
$script = <<<JS
$(document).ready(function(){

$('body').on('change','.attempt',function(){

if($(this).val()=="individual")
{
 $('.chooseq').prop('disabled','disabled');
 $('.deadlinedate').prop('disabled','');
  $('.deadlinetime').prop('disabled','');
  $('.numq').prop('disabled','');
  $('.chosenquestions').html("<div class='text-center text-lg text-muted mt-5 p-5'><i class='fa fa-info-circle'></i>Random Questions !</div>");
}
else
{
  $('.chosenquestions').html("");
  $('.chooseq').prop('disabled','');
  $('.deadlinedate').prop('disabled','disabled');
  $('.deadlinetime').prop('disabled','disabled');
  $('.numq').prop('disabled','disabled');


}

});
  
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




