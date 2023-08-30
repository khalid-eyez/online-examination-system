<?php
use frontend\models\ClassRoomSecurity;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use frontend\modules\assessments\models\QuizManager;
use common\models\Module;

?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
        <div class="row">
          <div class="col-sm-12 col-12 col-lg-12 col-md-12 p-0">
          <div class="container-fluid">
           <div class="card">
              <div class="card-body p-2 text-center text-success">
               
               <a href="<?=Url::to("questions-bank")?>"><i class="fa fa-bank"></i> Questions Bank</a>
               </div>

           </div>

        </div>

                </div>
          </div>
    
               <div class="accordion" id="bankaccordion">
               <?php
                 $questionsbuffer=(new QuizManager)->questionsBankReader();
                 //print_r($questionsbuffer); return false;

                 if($questionsbuffer==null)
                 {
                  ?>
                     <div class="container-fluid d-flex justify-content-center p-5"><span class="text-center text-muted text-lg"><i class="fa fa-info-circle"></i> Questions Bank Empty !</span></div>
                  <?php
                 // return false;
                  
                 }

                 //print_r($questionsbuffer);return false;

                 foreach($questionsbuffer as $chapter=>$bank)
                 {
                    ?>

                    <div class="card ">
                    <div class="card-header p-2" id="heading<?=$chapter?>">
                    <h2 class="mb-0">
                    <div class="row">
                    <div class="col-sm-10">
                    <button class="btn btn-link btn-block text-left col-md-11"
                     type="button" data-toggle="collapse"
                     data-target="#collapse<?=$chapter?>"
                      aria-expanded="true" 
                     aria-controls="collapse<?=$chapter?>">
                    <h7 class="responsiveheader"><img src="<?= Yii::getAlias('@web/img/module.png') ?>" width="20" height="20" class="mt-1"> <span class="assignment-header responsiveheader"><?=($chapter!="Others")?Module::findOne($chapter)->moduleName:$chapter?></span></h7>
                    
                    
                    </button>
                    </div>
        
                    </div>
                    </h2>
                    </div>

                    <div id="collapse<?=$chapter?>" class="collapse" aria-labelledby="heading<?=$chapter?>" data-parent="#bankaccordion">
                    <div class="card-body bg-white">
                    
                    <!------------------->

                         
                <div class="container-fluid qs">
                
                   <?php
                 
                
                 if(!empty($bank) || $bank!=null)
                 {
                 $bank=array_reverse($bank,true);
                 $count=1;
                 
                 foreach($bank as $index=>$question)
                 {
                   
                   /////////handling multiple choice questions
                  if($question['type']=="multiple-choice" || $question['type']=="true-false")
                  {
      
                   $options=$question['options'];
                   //print_r($options); return false;
                   ?>
                    <div class="row border-bottom" id=<?=$index?>>
                    <div class="col-sm-12 responsivetext rdiv d-none"><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
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
                      <input type="checkbox" name="quizQuestions[]" class="float-right mb-2 chooseq" cscore=<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> count=<?=count($options['true-choices'])?> value=<?=$index?>></input>
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
             
              
                 
                 ?>
                 <div class="row border-bottom" id=<?=$index?>>
                 <?php
                  if($question['questionImage']!=null)
                  {
                  ?> <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                  ?>
                 <div class="col-sm-12 responsivetext rdiv d-none"><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
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
                <input type="checkbox" name="quizQuestions[]" class="float-right mb-2 chooseq" cscore=<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>  count=<?=count($question['blanks'])?> value=<?=$index?>></input>
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
                 
                 <div class="row border-bottom" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv d-none"><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Mathing Items)</span>
                  <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                 
                 <?php
                 
                 foreach($question['items'] as $it=>$item)
                 {
                  if($item==null){continue;}
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
                <input type="checkbox" name="quizQuestions[]" class="float-right mb-2 chooseq" count=<?=$itemscount?> cscore=<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> value=<?=$index?>></input>
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
                 <div class="row border-bottom" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv d-none"><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
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
                <input type="checkbox" name="quizQuestions[]" class="float-right mb-2 chooseq" count=<?=count($question['alternatives'])?> cscore=<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>  value=<?=$index?>></input>
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
                 <div class="row border-bottom" id=<?=$index?>>
                 <div class="col-sm-12 responsivetext rdiv d-none"><span class="float-right"><a class="float-right qr" href="#"><i class="fa fa-trash text-primary text-xs tip" data-toggle="tooltip" data-title="Remove Question"></i></span></a></div>
                 <div class="col-sm-12 responsivetext pt-2 pb-5">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Short Answer)</span>
                  <span class="text-success text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                  <input type="checkbox" name="quizQuestions[]" class="float-right mb-2 chooseq markmanual" count=1 cscore=<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> value=<?=$index?>></input>
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
                 ?>
               
           
               </div>

           </div>

    
              </div>
              </div>

                   <!------------------------>
             <?php } ?>
                    </div>
                                            
                               
                    </div>
                    
                    </div>
               
            



<?php
$script = <<<JS
$(document).ready(function(){
$('body').on('change','.chooseq',function(){
  var name=$(this).prop('value');
 
    if(this.checked){
    var q=$(this).parent().parent().clone();
    q.find('.chooseq').hide();
    q.find('.rdiv').removeClass("d-none");
    q.find('.tip').tooltip();
    q.removeClass("border");
    q.appendTo('.chosenquestions');
   $(this).parent().parent().addClass('border');
   $(this).parent().parent().addClass('m-2');
   $(this).parent().parent().addClass('p-2');
   var tot=isNaN(parseFloat($('.totscore').val()))?0:parseFloat($('.totscore').val());
   var score=(isNaN(parseFloat($(this).attr('cscore')))?1:parseFloat($(this).attr('cscore')))*parseFloat($(this).attr('count'));
  $('.totscore').val(tot+score);
  }
  else
  {
  
    $('.chosenquestions').find($(document.getElementById(name))).remove();
    //.remove();
    $(this).parent().parent().removeClass('border');
    $(this).parent().parent().removeClass('m-2');
   $(this).parent().parent().removeClass('p-2');
   var tot=isNaN(parseFloat($('.totscore').val()))?0:parseFloat($('.totscore').val());
   var score=(isNaN(parseFloat($(this).attr('cscore')))?1:parseFloat($(this).attr('cscore')))*parseFloat($(this).attr('count'));
  $('.totscore').val(tot-score);
  }
})

$(document).on('click', '.chapdel', function(){
      var chap = $(this).attr('chapid');
      //alert(question); return true;
      Swal.fire({
  title: 'Delete All Questions?',
  text: "This process is irreversible !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete All !'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'empty-chapter-bank',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{chapter:chap},
      success:function(data){
        if(data.message){
          Swal.fire(
              'Deleted !',
              data.message,
              'success'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
   

        }
        else if(data.failed)
        {
          Swal.fire(
              'Failed !',
              data.failed,
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
<?php 
// $this->registerCssFile('@web/plugins/select2/css/select2.min.css');
// $this->registerJsFile(
//   '@web/plugins/select2/js/select2.full.js',
//   ['depends' => 'yii\web\JqueryAsset']
// );
// $this->registerJsFile(
//   '@web/js/create-assignment.js',
//   ['depends' => 'yii\web\JqueryAsset'],

// );



?>




