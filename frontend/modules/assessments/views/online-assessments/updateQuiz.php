<?php
use frontend\models\ClassRoomSecurity;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Module;

$this->title = 'Update Exam';
$instructor=yii::$app->user->identity->instructor->instructorID;
$modules=Module::find()->where(['instructorID'=>$instructor])->all();
$modules=ArrayHelper::map($modules,'moduleID','moduleName');
$modules['Others']='Others';

//quiz existing modules

$quizmodules=[];

if($quiz->attempt_mode=="massive")
{
foreach($buffer as $p=>$q)
{
  if(!isset($q['chapter']))
  {
    $q['chapter']="Others";
  }
  array_push($quizmodules,$q['chapter']);
}
}
else
{
  $quizmodules=$buffer['chapters'];
}

$types=(isset($buffer['qtypes']))?$buffer['qtypes']:[];
?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
       
        <div class="container-fluid">
           <div class="card ">
              <div class="card-body">
              <form  method="post">
              <div class="row">
                <div class="col-sm-3">
                 
                 <div class="row">
                    <div class="col-sm-12">
                    <input type="text" value="<?=$quiz->quiz_title?>" name="quizName" class="form-control" placeholder="Title, eg: test one, quiz 1" required></input>
                    </div>
               
                  </div>
                 </div>
                   <div class="col-sm-3">
                 <select  name="attemptMode" class="form-control attempt" required>
                   <option value="" selected disabled hidden>--Attempt Mode--</option>
                   <option value="massive" <?=$quiz->attempt_mode=="massive"?"selected":""?>>Massive Attempt (All Students At The Same Time)</option>
                   <option value="individual" <?=$quiz->attempt_mode=="individual"?"selected":""?>>Individual Attempt (Individual Random questions within a deadline)</option>
                 </select>
                 </div>
                 <div class="col-sm-3">
                 <input type="text" value="<?=explode(" ",$quiz->start_time)[0]?>" name="StartingDate" placeholder="Starting Date" onmouseover="(this.type='date')"  onfocus="(this.type='date')" onblur="(this.type='text')"  class="form-control" required></input>
                
                 </div>
                 <div class="col-sm-3">
                 <input type="text" value="<?=explode(" ",$quiz->start_time)[1]?>" name="StartingTime" placeholder="Starting Time" onmouseover="(this.type='time')"  onfocus="(this.type='time')" onblur="(this.type='text')" class="form-control float-left " required></input>
                 </div>
               </div>
               <div class="row mt-1">
                 <div class="col-sm-3">
                 <input type="text" value="<?=$quiz->attempt_mode=="individual"?explode(" ",$quiz->end_time)[0]:""?>" name="DeadlineDate" placeholder="Deadline Date" onmouseover="(this.type='date')"  onfocus="(this.type='date')" onblur="(this.type='text')"  class="form-control deadlinedate" required></input>
                 </div>
                 <div class="col-sm-3">
                 <input type="text" value="<?=$quiz->attempt_mode=="individual"?explode(" ",$quiz->end_time)[1]:""?>" name="DeadlineTime" placeholder="Deadline Time" onmouseover="(this.type='time')" onfocus="(this.type='time')" onblur="(this.type='text')" class="form-control float-left deadlinetime" required></input>
                 </div>
                 <div class="col-sm-3">
                 <div class="row pr-1 pl-1">
                   <div class="col-sm p-0">
                 <input type="text" value="<?=$quiz->duration?>" name="duration" class="form-control" placeholder="Duration(min)" required></input>
                 </div>
                 <div class="col-sm p-0  ml-1">
                 <input type="text" value="<?=$quiz->attempt_mode=="individual"?$quiz->num_questions:''?>" name="numquestions" class="form-control numq" placeholder="no. questions" required></input>
                 </div>
                 <div class="col-sm p-0  ml-1">
                 <input type="text" name="total_score" value="<?=$quiz->total_marks?>" class="form-control totscore " placeholder="Total Score"></input>
                 </div>
                 </div>
                 </div>

                 <div class="col-sm">
                 <select class="form-control" multiple="multiple" id="modules" name='chapters[]' style="width:100%">
                  <?php
                   foreach($modules as $index=>$modulename)
                   {
                    ?>

                    <option value="<?=$index?>" <?=in_array($index,$quizmodules)?"selected":""?>><?=$modulename?></option>
                    <?php
                   }
                  ?>
                 </select>
                 </div>
                  <div class="col-sm p-0">
                
                  <select class="form-control" multiple="multiple" id="qtypes" name='qtypes[]'style="width:100%">
                  <option value="multiple-choice" <?=($types!=null && in_array("multiple-choice",$types))?"selected":""?>>Multiple choices</option>
                  <option value="true-false" <?=($types!=null && in_array("true-false",$types))?"selected":""?>>True/False</option>
                  <option value="fill-in-blanks" <?=($types!=null && in_array("fill-in-blanks",$types))?"selected":""?>>Fill-in-blanks</option>
                  <option value="matching" <?=($types!=null && in_array("matching",$types))?"selected":""?>>Matching Items</option>
                  <option value="enum" <?=($types!=null && in_array("enum",$types))?"selected":""?>>Listing(Enum)</option>
                  <option value="shortanswer" <?=($types!=null && in_array("shortanswer",$types))?"selected":""?>>Short Answer</option>
                  </select>

                  </div>
               </div>
               
               </div>

           </div>
          
   
        <div class="row p-2 ">
           <div class="col-sm-7 bg-white p-5" style="max-height:500px!important;overflow:auto">
           <div class="container mb-4 text-lg text-muted border-bottom">Chosen Questions Appear Here</div>
             <div class="container chosenquestions">
               <!--//////////////////////////////quiz existing questions/////////////////////////////////////   -->
               <?php if($quiz->attempt_mode=="massive"){?>
              <?php
               if(!empty($buffer) || $buffer!=null)
                 {
                
                 $count=1;
                 
                 foreach($buffer as $index=>$question)
                 {
             
                   /////////handling multiple choice questions
                  if($question['type']=="multiple-choice" || $question['type']=="true-false")
                  {
      
                   $options=$question['options'];
                   ?>
                    <div class="row border-bottom que" id=<?=$index?>>
                    <div class="col-sm-12 responsivetext rdiv"><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                     <div class="col-sm-12 responsivetext"><?=$count.". ".htmlspecialchars($question['question'])." "?>
                     <span class="text-muted responsivetext">
                      <?=($question['multiple']=='on')?"(Choose Many)":"(Choose One)"?>
                    </span>
                 
                    <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                
                    </div>
                     <?php
                      if($question['questionImage']!=null)
                      {
                        ?>
                        <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">
                        <?php
                      }
                     ?>
                     <div class="responsivetext col-sm-12">
                      <?php
                        if($options['type']=="textual")
                        {
                          foreach($options['choices'] as $i=>$choice)
                          {
            
                              if(array_key_exists($i,$options['true-choices']))
                              {
                          ?>
                             <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($choice)?></li>
                          <?php
                         
                              }
                              else
                              {
                                ?>
                                <li class="ml-4  text-muted responsivetext"><?=htmlspecialchars($choice)?></li>
                             <?php
                           
                              }
                            
                          }
                        }
                        else
                        {
                          foreach($options['choices'] as $ind=>$choice)
                          {
                            if(array_key_exists($ind,$options['true-choices']))
                            {

                      ?>
                      <li class="ml-4 p-2 text-success"><img class="img-thumbnail border-success" src="/<?=$choice?>" width=60 height=40></li>
                      <?php
                            }
                            else
                            {
                              ?>
                              <li class="ml-4 p-2"><img class="img-thumbnail" src="/<?=$choice?>" width=60 height=40></li>
                              <?php
                            }
                          }
                        }
                      ?>
                      
                     </div>
                 </div>

                 
                   <?php
                  
                 }
                  ////////////// end of multipe choice and true-false questions
                 /////////////// handling fill-in-blanks questions////////////
                 else if($question['type']=='fill-in-blanks')
                 {
                  $questiondesc=explode("[#### blank",$question['question']);
                  $questiondesc=implode("__________",$questiondesc);
             
                  if($question['questionImage']!=null)
                  {
                  ?> <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                 
                 ?>
                 <div class="row border-bottom que" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv "><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($questiondesc)." "?><span class="text-muted responsivetext">(Fill In Blanks)</span>
                  <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
             
                 <?php
                 
                 foreach($question['blanks'] as $ix=>$blank)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($blank)?></li>
                  <?php
                   
                
                 }
                ?>
               
                 </div>
                 </div>
                <?php
                 }

                 /////////// end of  fill-in-blanks questions
                 /////// handling matching items questions
                 else if($question['type']=='matching')
                 {
                  if($question['questionImage']!=null)
                  {
                  ?> <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                 
                 ?>
                 
                 <div class="row border-bottom que" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv "><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Mathing Items)</span>
                  <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                 
                 <?php
                 
                 foreach($question['items'] as $it=>$item)
                 {
                  if($item==null){ continue;}
                  ?>
                   <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$it + 1?>. <?=htmlspecialchars($item)?></li>
                  <?php
                   
                
                 }
                ?>
                
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                 
                 <?php
                 
                 foreach($question['matches'] as $m=>$match)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$m?>. <?=htmlspecialchars($match)?></li>
                  <?php
                   
                
                 }
                ?>
                
                </div>
                </div>
                <div class="row">

                  <div class="col-12 col-sm-12 ">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                  <tr>
                  <?php
                 $itemscount=0;
                 foreach($question['items'] as $im=>$item)
                 {
                   if($item!=null)
                   {
                  ?>
                   <td><?=$im +1 ?></td>
                  <?php
                  $itemscount++;
                   }
               
                 }
                ?>
                </tr>
                    <tr>
                  <?php
                 $keys=array_keys($question['matches']);
                 for($i=0;$i<$itemscount;$i++)
                 {
                  
                  ?>
                   <td><?=htmlspecialchars($keys[$i])?></td>
                  <?php
               
                 }
                ?>
                </tr>
                </table>
                </div>
                  </div>

                </div>
                
                 </div>
                 </div>
                <?php
                 }
                 //////the end of matching items question
                 /////handling enumaration questions
                 else if($question['type']=='enum')
                 {
                  if($question['questionImage']!=null)
                  {
                  ?> <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                 
                 ?>
                 <div class="row border-bottom que" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv "><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Outline)</span>
                  <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                 <?php
                 
                 foreach($question['alternatives'] as $a=>$alt)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($alt)?></li>
                  <?php
                   
                
                 }
                ?>
                
                 </div>
                 </div>
                <?php
                 }
                 //////////// the end of enumaration questions
                 /////////// handling short answer questions
                 else if($question['type']=='shortanswer')
                 {
                  if($question['questionImage']!=null)
                  {
                  ?>
                   <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                 
                 ?>
                 <div class="row border-bottom que" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv "><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                 <div class="col-sm-12 responsivetext pt-2 pb-5">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Short Answer)</span>
                  <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                 </div>
                 </div>
                <?php
                 }
                 $count++;
                 //// the end of short answer questions
                 ?>
                 
                 <?php
                   
                  }
                }
                }
                  ?>
               <!--/////////////////////////////// end of questions //////////////////////////////////////     -->
             </div>
         
          </div>
           <div class="col-sm-5 p-3 m-0 bg-white " style="max-height:500px!important;overflow:auto">
             <?=$this->render("questionsBank2")?>
           </div>
         </div>
       
         <div class="row mb-2 shadow">
           <div class="col-sm-12 bg-white d-flex justify-content-center p-3 ">
          <button type="submit" class="btn btn-success btn-md  shadow"><i class="fa fa-save"></i> Save Changes</button>
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
  $("#CourseList").DataTable({
    responsive:true,
  });
  $('#qtypes').select2({
    placeholder:"--Select Types--",
    allowClear:true
  });
  $('#modules').select2({
    placeholder:"--Select Modules--",
    allowClear:true
  });
if($('.attempt').val()=="individual")
{
  $('.totscore').prop('disabled','');
  $('#qtypes').prop('disabled','');
}
else
{
  $('#qtypes').prop('disabled','disabled');
  $('.totscore').prop('disabled','disabled');
}
$('body').on('change','.attempt',function(){

if($(this).val()=="individual")
{ 
$('.chosenquestions').html("");
$('#qtypes').prop('disabled','');
 $('.chooseq').prop('disabled','disabled');
 $('#modules').prop('disabled','');
 $('.totscore').prop('disabled','');
 $('.deadlinedate').prop('disabled','');
  $('.deadlinetime').prop('disabled','');
  $('.numq').prop('disabled','');
  $('.chosenquestions').html("<div class='text-center text-lg text-muted mt-5 p-5'><i class='fa fa-info-circle'></i>Random Questions !</div>");
}
else
{
  //window.location.reload();
  $('#qtypes').prop('disabled','disabled');
  $('.totscore').prop('disabled','disabled');
  $('.chooseq').prop('disabled','');
  $('.chosenquestions').html("");
  $('#modules').prop('disabled','disabled');
  $('.deadlinedate').prop('disabled','disabled');
  $('.deadlinetime').prop('disabled','disabled');
  $('.numq').prop('disabled','disabled');
  if($('.markingmode').val()=="automatic")
  {
    $('.markmanual').prop('disabled',true);
  }


}

});
if($('.attempt').val()=="individual")
{
  $('#modules').prop('disabled','');
 $('.chosenquestions').html("");
 $('.chooseq').prop('disabled','disabled');
 $('.deadlinedate').prop('disabled','');
  $('.deadlinetime').prop('disabled','');
  $('.numq').prop('disabled','');
  $('.chosenquestions').html("<div class='text-center text-lg text-muted mt-5 p-5'><i class='fa fa-info-circle'></i>Random Questions !</div>");

}
else
{
 
  $('.chooseq').prop('disabled','');
  
  $('#modules').prop('disabled','disabled');
  
  $('.deadlinedate').prop('disabled','disabled');
  $('.deadlinetime').prop('disabled','disabled');
  $('.numq').prop('disabled','disabled');
  if($('.markingmode').val()=="automatic")
  {
    $('.markmanual').prop('disabled',true);
  }


}
$('body').on('click','.qr',function(){

var id=$(this).parent().parent().parent().attr("id");
$('.qs').find("#"+id).find('.chooseq').prop("checked",false);
$('.qs').find("#"+id).removeClass("border");
$(this).parent().parent().parent().remove();
});

var questions=$('.que');
for( var q=0;q<questions.length; q++)
{
      var id=questions.eq(q).attr("id");
      var questioninbank=$('.qs').find('#'+id);
      questioninbank.find('.chooseq').prop("checked",true);     
}
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
$this->registerJsFile(
  '@web/js/create-assignment.js',
  ['depends' => 'yii\web\JqueryAsset'],

);



?>




