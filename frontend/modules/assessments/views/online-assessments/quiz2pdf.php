<?php
use frontend\models\ClassRoomSecurity;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use frontend\models\QuizManager;


/* @var $this yii\web\View */
$cid=yii::$app->session->get('ccode');
$this->params['courseTitle'] ="<i class='fa fa-pen'></i> Quiz Preview";
$this->title = 'Quizs Preview';
$this->params['breadcrumbs'] = [
    ['label'=>$cid.' Quizes', 'url'=>Url::to(['class-quizes','cid'=>ClassRoomSecurity::encrypt(yii::$app->session->get("ccode"))])],
    ['label'=>$this->title]
];

?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->

 
        <div class="container-fluid pl-5 pr-5">
           <div class="card shadow" >
    
               <?php
                 if(!empty($quizdata) || $quizdata!=null)
                 {
                  
                
                 $count=1;
                 
                 foreach($quizdata as $index=>$question)
                 {
                  if($question['type']=="true-false" || $question['type']=="multiple-choice")
                  {
                   $options=$question['options'];
                   ?>
                    <div class="row mt border-bottom">
                     <div class="col-sm-12 responsivetext"><?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext"><?=($question['multiple']=='on')?"(Choose Many)":"(Choose One)"?></span><br>
                     <span class="text-success text-sm text-bold">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger text-sm text-bold">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                     <?php
                      if($question['questionImage']!=null)
                      {
                        ?>
                        <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">
                        <?php
                      }
                     ?>
                     <div class="responsivetext col-sm-12">
                       <ol type="A">
                      <?php
                        if($options['type']=="textual")
                        {
                          foreach($options['choices'] as $index=>$choice)
                          {
            
                          
                                ?>
                                <li class="ml-4  text-muted responsivetext"><?=htmlspecialchars($choice)?></li>
                             <?php
                           
                              
                            
                          }
                        }
                        else
                        {
                          foreach($options['choices'] as $index=>$choice)
                          {
                           
                              ?>
                              <li class="ml-4 p-2"><img class="img-thumbnail" src="/<?=$choice?>" width=60 height=40></li>
                              <?php
                            
                          }
                        }
                      ?>
                      </ol>
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
                 <div class="row mt border-bottom">
                 <div class="col-sm-12 responsivetext">
                
                  <?=$count.". ".htmlspecialchars($questiondesc)." "?><span class="text-muted responsivetext">(Fill In Blanks)</span><br>
                  <span class="text-success text-sm text-bold">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger text-sm text-bold">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span><br>
                  <textarea class="mt1" rows=<?=count(explode("[#### blank",$question['question']))?>  style="width:100%">&nbsp;
                  </textarea>
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
                 
                 <div class="row mt border-bottom">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Matching Items)</span><br>
                  <span class="text-success text-sm text-bold">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger text-sm text-bold">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                  <table class="table" width="100%" style="border:none;margin-bottom:4px">
                  <tr>
                    <td class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="float:right;border:none">
                    <span  style="background-color:#def;padding:3px;margin:2px">SIDE A</span>
                 <ul>
                 <?php
                 
                 foreach($question['items'] as $index=>$item)
                 {
                  if($item!=null){
                  ?>
                   <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$index + 1?>. <?=htmlspecialchars($item)?></li>
                  <?php
                   
                  }
                 }
                ?>
                </ul>
                </td>
                <td class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="float:right;border:none">
                <span  style="background-color:#def;padding:3px;margin:2px">SIDE B</span>
                 <ul style="list-style-type: none;">
                 <?php
                 
                 foreach($question['matches'] as $index=>$match)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$index?>. <?=htmlspecialchars($match)?></li>
                  <?php
                   
                
                 }
                ?>
                </ul>
                </td>
                </tr>
                </table>
                <div class="row mt1" style="width:100%">

                  <div class="col-12 col-sm-12 " style="width:100%">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" border='1' cellspacing=0 width="100%" style="min-width:400px;">
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
                   <td height="20px" ><?=null?></td>
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
                 <div class="row mt border-bottom">
                 <div class="col-sm-12 responsivetext">
             
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Outline)</span><br>
                  <span class="text-success text-sm text-bold">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger text-sm text-bold">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                  <textarea class="mt1" rows=<?=count($question['alternatives'])?>  style="width:100%">&nbsp;
                  </textarea>
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
                 <div class="row mt border-bottom">
                 <div class="col-sm-12 responsivetext pt-2 pb-5">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Short Answer)</span><br>
                  <span class="text-success text-sm text-bold">[ correct: <?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?> Mark(s) each ]</span>
                  <span class="text-danger text-sm text-bold">[ incorrect: <?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?> Mark(s) each ]</span>
                  <textarea class="mt1" rows=20  style="width:100%">&nbsp;
                  </textarea>
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
        <!--
        question add modal


    </div>


<?php
$script = <<<JS
$(document).ready(function(){
  $("#CourseList").DataTable({
    responsive:true,
  });
//Remember active tab
$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

localStorage.setItem('activeTab', $(e.target).attr('href'));

});

var activeTab = localStorage.getItem('activeTab');

if(activeTab){

$('#custom-tabs-four-tab a[href="' + activeTab + '"]').tab('show');

}

$(document).on('click', '.qdel', function(){
      var question = $(this).attr('id');
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
      url:'/quiz/delete-question',
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




