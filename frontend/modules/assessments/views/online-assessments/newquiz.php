<?php
use frontend\models\ClassRoomSecurity;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Module;

$this->title = 'New Exam';

$instructor=yii::$app->user->identity->instructor->instructorID;
//finding the existing  modules
$modules=Module::find()->where(['instructorID'=>$instructor])->all();
$modules=ArrayHelper::map($modules,'moduleID','moduleName');
$modules['Others']='Others';
?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
       
        <div class="container-fluid">
           <div class="card">
              <div class="card-body">
              <form action="create-quiz" method="post">
              <div class="row">
                <div class="col-sm-3">
                  <div class="row">
                    <div class="col-sm-12">
                    <input type="text" name="quizName" class="form-control" placeholder="Title, eg: test one, quiz 1" required></input>
                    </div>
                  </div>
                 
                 </div>
                   <div class="col-sm-3">
                 <select  name="attemptMode" class="form-control attempt" required>
                   <option value="" selected disabled hidden>--Attempt Mode--</option>
                   <option value="massive">Massive Attempt (All Students At The Same Time)</option>
                   <option value="individual">Individual Attempt (Individual Random questions within a deadline)</option>
                 </select>
                 </div>
                 <div class="col-sm-3">
                 <input type="text" name="StartingDate" placeholder="Starting Date" onmouseover="(this.type='date')"  onfocus="(this.type='date')" onblur="(this.type='text')"  class="form-control" required></input>
                
                 </div>
                 <div class="col-sm-3">
                 <input type="text" name="StartingTime" placeholder="Starting Time" onmouseover="(this.type='time')"  onfocus="(this.type='time')" onblur="(this.type='text')" class="form-control float-left " required></input>
                 </div>
               </div>
               <div class="row mt-1">
                 <div class="col-sm-3">
                 <input type="text" name="DeadlineDate" placeholder="Deadline Date" onmouseover="(this.type='date')"  onfocus="(this.type='date')" onblur="(this.type='text')"  class="form-control deadlinedate" required></input>
                 </div>
                 <div class="col-sm-3">
                 <input type="text" name="DeadlineTime" placeholder="Deadline Time" onmouseover="(this.type='time')" onfocus="(this.type='time')" onblur="(this.type='text')" class="form-control float-left deadlinetime" required></input>
                 </div>
                 <div class="col-sm-3">
                 <div class="row pr-1 pl-1">
                   <div class="col-sm p-0 mr-1">
                 <input type="text" name="duration" class="form-control p-1" placeholder="Duration(min)" required></input>
                 </div>
                 <div class="col-sm p-0  ml-1">
                 <input type="text" name="numquestions" class="form-control numq p-1" placeholder="no. questions" required></input>
                 </div>
                 <div class="col-sm p-0  ml-1">
                 <input type="text" name="total_score" class="form-control totscore " placeholder="Total Score"></input>
                 </div>
                 </div>
                 </div>

                 <div class="col-sm">
  
                 <select class="form-control" multiple="multiple" id="modules" name='chapters[]'style="width:100%">
                  <?php
                   foreach($modules as $index=>$modulename)
                   {
                    ?>

                    <option value="<?=$index?>"><?=htmlspecialchars($modulename)?></option>
                    <?php
                   }
                  ?>
                 </select>
                 
               </div>
                <div class="col-sm p-0">

                <select class="form-control" multiple="multiple" id="qtypes" name='qtypes[]'style="width:100%">
                <option value="multiple-choice">Multiple choices</option>
                <option value="true-false">True/False</option>
                <option value="fill-in-blanks">Fill-in-blanks</option>
                <option value="matching">Matching Items</option>
                <option value="enum">Listing(Enum)</option>
                <option value="shortanswer">Short Answer</option>
                </select>

                </div>
               
               </div>

           </div>
           </div>
          
      
        <div class="row p-2 ">
           <div class="col-sm-7 bg-white p-5" style="max-height:500px!important;overflow:auto">
           <div class="container mb-4 text-lg text-muted border-bottom">Chosen Questions Appear Here</div>
             <div class="container chosenquestions">
              
             </div>
         
          </div>
           <div class="col-sm-5 p-3 m-0 bg-white " style="max-height:500px!important;overflow:auto">
             <?=$this->render("questionsBank2")?>

           </div>
         </div>
         <div class="row mb-2 shadow">
           <div class="col-sm-12 bg-white d-flex justify-content-center p-3 ">
          <button type="submit" class="btn btn-success btn-md shadow"><i class="fa fa-save"></i> Save Exam</button>
          </div>
         </div>
        </div>
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
</form>
    </div>
    </div>
   
<?php
$script = <<<JS
$(document).ready(function(){
  $('#modules').select2({
    placeholder:"--Select Modules--",
    allowClear:true
  });
  $('#qtypes').select2({
    placeholder:"--Select Types--",
    allowClear:true
  });
$('body').on('change','.attempt',function(){

if($(this).val()=="individual")
{
  $('.chosenquestions').html("");
 $('.chooseq').prop('disabled','disabled');
 $('#qtypes').prop('disabled','');
 $('.deadlinedate').prop('disabled','');
  $('.deadlinetime').prop('disabled','');
  $('.totscore').prop('disabled','');
  $('.numq').prop('disabled','');
  $('#modules').prop('disabled','');
  $('.chosenquestions').html("<div class='text-center text-lg text-muted mt-5 p-5'><i class='fa fa-info-circle'></i>Random Questions !</div>");
  /*Swal.fire(
    "Tip !",
    "This type will allow a student to take the quiz at his favourable time within a specified deadline, students will get individual quiz/test version with randomly selected questions from your questions bank. Make sure you have enough questions in your bank to avoid some questions or versions repeating several times leading to cheating during the quiz/test.",
    'info'
  )*/
}
else
{
  $('#modules').prop('disabled','disabled');
  $('.chooseq').prop('disabled','');
  $('#qtypes').prop('disabled','disabled');
  $('.deadlinedate').prop('disabled','disabled');
  $('.deadlinetime').prop('disabled','disabled');
  $('.totscore').prop('disabled','disabled');
  $('.numq').prop('disabled','disabled');
  /*Swal.fire(
    "Tip !",
    "This type will constrain students to take the quiz/test all at once and at the same time, students have the same version of quiz/test as per your definition. Mind our server limit! This server may slow down in case of very big classes !",
    'info'
  )*/

  

}

});
$('body').on('click','.qr',function(){
  var id=$(this).parent().parent().parent().attr("id");
  $('.qs').find("#"+id).find('.chooseq').prop("checked",false);
  $('.qs').find("#"+id).removeClass("border");
  var qsc=$(this).parent().parent().parent().find('.chooseq');
  var tot=isNaN(parseFloat($('.totscore').val()))?0:parseFloat($('.totscore').val());
  var score=(isNaN(parseFloat(qsc.attr('cscore')))?1:parseFloat(qsc.attr('cscore')))*parseFloat(qsc.attr('count'));
  $('.totscore').val(tot-score);
  $(this).parent().parent().parent().remove();
})
  $('body').addClass("sidebar-collapse");

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
// $this->registerJsFile(
//   '@web/js/create-assignment.js',
//   ['depends' => 'yii\web\JqueryAsset'],

// );



?>




