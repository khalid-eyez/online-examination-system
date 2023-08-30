<?php
use frontend\models\ClassRoomSecurity;
use yii\helpers\Url;
use common\models\Module;
use frontend\modules\assessments\models\QuizManager;
/* @var $this yii\web\View */
$cid=yii::$app->session->get('ccode');


?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
        <div class="row">
          <div class="col-sm-12 col-12 col-lg-10 col-md-9 p-0">
        <div class="container-fluid">
           <div class="card shadow-sm">
              <div class="card-body p-3 pr-1 text-center">
               
               <a href="#" class="text-success" data-toggle="modal" data-target="#questionmodal"><i class="fa fa-plus-circle"></i> New Question</a>

                </div>
              </div>

                </div>

                </div>
                <div class="col-sm-12 col-12 col-md-3 col-lg-2 p-0 ">
                <div class="container-fluid">
           <div class="card shadow-sm">
              <div class="card-body p-2 pl-1 text-center">
                <?php $bankfile=(new QuizManager)->getBankHome()?>
               <a href="#" class="btn btn-default shadow-sm text-success" data-toggle="modal" data-target="#uploadermodal" ><i class="fa fa-upload" data-toggle="tooltip" data-title="Upload Questions Bank File" ></i></a>
               <a href="/<?=$bankfile?>" class="btn btn-default shadow-sm text-success" data-toggle="tooltip" data-title="Download Questions Bank File"  ><i class="fa fa-download"></i></a>
               <a href="<?=Url::to("download-bank")?>" class="btn btn-default shadow text-primary" data-toggle="tooltip" data-title="Download As PDF"  ><i class="fa fa-file-pdf-o text-danger"></i></a>
               </div></div></div></div></div>
    
               <div class="accordion" id="bankaccordion">
               <?php
               //return false;
                 $questionsbuffer=(new QuizManager)->questionsBankReader();
                 //print_r($questionsbuffer); return false;

                 if($questionsbuffer==null)
                 {
                  ?>
                     <div class="container-fluid d-flex justify-content-center p-5"><span class="text-center text-muted text-lg"><i class="fa fa-info-circle"></i> Questions Bank Empty !</span></div>
                  <?php

                  
                 }

                 //print_r($questionsbuffer);return false;

                 foreach($questionsbuffer as $chapter=>$bank)
                 {
                    $module=Module::findOne($chapter);
          
                    ?>

                    <div class="card shadow-sm">
                    <div class="card-header p-2" id="heading<?=$chapter?>">
                    <h2 class="mb-0">
                    <div class="row">
                    <div class="col-sm-10">
                    <button class="btn btn-link btn-block text-left col-md-11"
                     type="button" data-toggle="collapse"
                     data-target="#collapse<?=$chapter?>"
                      aria-expanded="true" 
                     aria-controls="collapse<?=$chapter?>">
                    <h5 class="responsiveheader"><img src="<?= Yii::getAlias('@web/img/module.png') ?>" width="30" height="30" class="mt-1"> <span class="assignment-header responsiveheader"><?php echo ($module!=null)?$module->moduleName:$chapter;?></span></h5>
  
                    
                    </button>
                    </div>
                    <div class="col-sm-2">
                    <a href="#" chapid=<?=$chapter?> class="chapdel btn btn-sm btn-default text-danger ml-1 float-right" data-toggle="tooltip" data-title="Delete All Questions"><i class="fa fa-trash"></i></a>
                    <a href="<?=Url::to(['/assessments/online-assessments/chapter-bank','chapter'=>ClassRoomSecurity::encrypt($chapter)])?>" class="btn btn-sm btn-default text-primary float-right" data-toggle="tooltip" data-title="Go To Module Bank" ><i class="fa fa-arrow-right " ></i></a>
                    
                    </div>
                    </div>
                    </h2>
                    </div>

                    <div id="collapse<?=$chapter?>" class="collapse" aria-labelledby="heading<?=$chapter?>" data-parent="#bankaccordion">
                    <div class="card-body bg-white">
                    
                    <!------------------->

                         
                <div class="container-fluid qs">
                
                   <?php
                 
                 // print_r($bank); return false;
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
                   ?>
                    <div class="row border-bottom">
                     <div class="col-sm-12 responsivetext"><?=$count.". ".htmlspecialchars($question['question'])." "?>
                     <span class="text-muted responsivetext">
                      <?=($question['multiple']=='on')?"(Choose Many)":"(Choose One)"?>
                      <span class="text-success">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                      <span class="text-danger">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                    </span>
                     <span class="float-right">
                      <a class="float-right qdel" href="#" id=<?=$index?>>
                      <i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question">
                      </i>
                      </a>
                    </span>

                    <span class="float-right">
                      <a class="float-right" href="<?=Url::to(['update-question','q'=>ClassRoomSecurity::encrypt($index)])?>" >
                      <i class="fa fa-edit text-sm mr-1" data-toggle="tooltip" data-title="Update Question">
                      </i>
                      </a>
                    </span>
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
                          foreach($options['choices'] as $index=>$choice)
                          {
            
                              if(array_key_exists($index,$options['true-choices']))
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
                          foreach($options['choices'] as $index=>$choice)
                          {
                            if(array_key_exists($index,$options['true-choices']))
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
                 <div class="row border-bottom">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($questiondesc)." "?><span class="text-muted responsivetext">(Fill In Blanks)</span>
                  <span class="text-success">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                  <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
                 <span class="float-right">
                      <a class="float-right" href="<?=Url::to(['update-question','q'=>ClassRoomSecurity::encrypt($index)])?>" >
                      <i class="fa fa-edit text-sm mr-1" data-toggle="tooltip" data-title="Update Question">
                      </i>
                      </a>
                    </span>
                 <?php
                 
                 foreach($question['blanks'] as $index=>$blank)
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
                 
                 <div class="row border-bottom">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Mathing Items)</span>
                  <span class="text-success">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
                 <span class="float-right">
                      <a class="float-right" href="<?=Url::to(['update-question','q'=>ClassRoomSecurity::encrypt($index)])?>" >
                      <i class="fa fa-edit text-sm mr-1" data-toggle="tooltip" data-title="Update Question">
                      </i>
                      </a>
                    </span>
                  <div class="row mt-2">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <span class="p-1 pr-5 ml-4 col" style="background-color:#def">SIDE A</span>
                 
                 <?php
                 
                 foreach($question['items'] as $index=>$item)
                 {
                  if($item==null){continue;}
                  ?>
                   <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$index + 1?>. <?=htmlspecialchars($item)?></li>
                  <?php
                   
                
                 }
                ?>
                
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                 <span class="p-1 pr-5 ml-4 col" style="background-color:#def">SIDE B</span>
                 <?php
                 
                 foreach($question['matches'] as $index=>$match)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$index?>. <?=htmlspecialchars($match)?></li>
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
                 foreach($question['items'] as $index=>$item)
                 {
                   if($item!=null)
                   {
                  ?>
                   <td><?=$index +1 ?></td>
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
                 <div class="row border-bottom">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Outline)</span>
                  <span class="text-success">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
                 <span class="float-right">
                      <a class="float-right" href="<?=Url::to(['update-question','q'=>ClassRoomSecurity::encrypt($index)])?>" >
                      <i class="fa fa-edit text-sm mr-1" data-toggle="tooltip" data-title="Update Question">
                      </i>
                      </a>
                    </span>
                 <?php
                 
                 foreach($question['alternatives'] as $index=>$alt)
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
                 <div class="row border-bottom">
                 <div class="col-sm-12 responsivetext pt-2 pb-5">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Short Answer)</span>
                  <span class="text-success">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
                 <span class="float-right">
                      <a class="float-right" href="<?=Url::to(['update-question','q'=>ClassRoomSecurity::encrypt($index)])?>" >
                      <i class="fa fa-edit text-sm mr-1" data-toggle="tooltip" data-title="Update Question">
                      </i>
                      </a>
                    </span>
          
                 </div>
                 </div>
                <?php
                 }
                 //// the end of short answer questions
                 $count++;
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
               
            
        <!--
        question add modal

        -->
<?=
$this->render('newQuestion');

?>
<?=
$this->render('questionsUploader');

?>
    </div>


<?php
$script = <<<JS
$(document).ready(function(){

$(document).on('click', '.qdel', function(){
      var question = $(this).attr('id');
      //alert(question); return true;
      Swal.fire({
  title: 'Delete Question?',
  text: "You won't be able to revert this!",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete !'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'delete-question',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{question:question},
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
      }
    })
   
  }
})

});

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




