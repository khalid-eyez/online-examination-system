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
use yii\base\UserException;
use common\models\Quiz;
?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->

 
        <div class="container-fluid pl-5 pr-5">
           <div class="card shadow" >
               <?php
                 $count=1;
                 foreach($submitted as $index=>$res)
                 {
                  try
                  {
                    $question=(new QuizManager(null,$instructor,[]))->findQuestion($index);
                  }
                  catch(UserException $e)
                  {
                    continue;
                  }
                 if($question['type']=='shortanswer')
                 {
                  if($question['questionImage']!=null)
                  {
                  ?>
                   <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                 
                 ?>
                 <div class="row border-bottom">
                 <p>
                  <b><?=$count.". ".htmlspecialchars($question['question'])." "?></b>
                  <span class="text-success  text-bold"><b>[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</b></span>
                  <span class="text-danger  text-bold"><b>[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</b></span>
                </p>  
                 <p>
                 <b> RES:</b> <i><pre style="color:blue"><code style="font-style:italic"><?=$res!=null?htmlspecialchars($res['answer']):null?></code></pre></i>
              
          
                </p>

                 </div>
                <?php
                  
                 }
                 //// the end of short answer questions

                 ///////////////////////////////////

                 else if($question['type']=="true-false" || $question['type']=="multiple-choice")
                 {
                  $options=$question['options'];
                  ?>
                   <div class="row border-bottom">
                    <div class="col-sm-12 responsivetext"><b><?=$count.". ".htmlspecialchars($question['question'])." "?></b><span class="text-muted responsivetext"><?=($question['multiple']=='on')?"(Choose Many)":"(Choose One)"?></span>
                    <span class="text-success  text-bold"><b>[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</b></span>
                  <span class="text-danger  text-bold"><b>[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</b></span>
                 </div>
                    <?php
                     if($question['questionImage']!=null)
                     {
                       ?>
                       <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="200">
                       <?php
                     }
                    ?>
                    <div class="responsivetext col-sm-12">
                      <ul>
                     <?php
                       if($options['type']=="textual")
                       {
                         foreach($res as $index=>$choice)
                         {
                          if($index=="'nothing'"){continue;}
                             if($question['multiple']!='on')
                             {
                             if(array_key_exists($choice,$options['true-choices']))
                             {
                              $truechoice=$options['choices'][intval($choice)];
                         ?>
                            <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($truechoice)?> [+<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</li>
                         <?php
                        
                             }
                             else
                             {
                              $truechoice=$options['choices'][intval($choice)];
                               ?>
                               <li class="ml-4  text-muted responsivetext text-danger"><?=htmlspecialchars($truechoice)?>  [<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</li>
                            <?php
                          
                             }
                            }
                            else
                            {
                              if(array_key_exists($index,$options['true-choices']))
                              {
                               $truechoice=$options['choices'][intval($index)];
                          ?>
                             <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($truechoice)?> [+<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</li>
                          <?php
                         
                              }
                              else
                              {
                               $truechoice=$options['choices'][intval($index)];
                                ?>
                                <li class="ml-4  text-muted responsivetext text-danger"><?=htmlspecialchars($truechoice)?>  [<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</li>
                             <?php
                           
                              }
                            }
                           
                         }
                       }
                       else
                       {
                         foreach($res as $index=>$choice)
                         {
                          if($index=="'nothing'"){continue;}
                          if($question['multiple']!='on')
                          {
                           if(array_key_exists($choice,$options['true-choices']))
                           {

                     ?>
                     <li class="ml-4 p-2 text-success"><img class="img-thumbnail border-success" src="/<?=$truechoice=$options['choices'][intval($choice)];?>" width=60 height=40>
                    [+<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]
                    </li>
                     
                     <?php
                           }
                           else
                           {
                             ?>
                             <li class="ml-4 p-2 text-danger"><img class="img-thumbnail" src="/<?=$truechoice=$options['choices'][intval($choice)];?>" width=60 height=40>
                             [<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]
                            </li>
                             <?php
                           }
                          }
                          else
                          {
                            if(array_key_exists($index,$options['true-choices']))
                           {

                     ?>
                     <li class="ml-4 p-2 text-success"><img class="img-thumbnail border-success" src="/<?=$truechoice=$options['choices'][intval($choice)];?>" width=60 height=40>
                    [+<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]
                    </li>
                     
                     <?php
                           }
                           else
                           {
                             ?>
                             <li class="ml-4 p-2 text-danger"><img class="img-thumbnail" src="/<?=$truechoice=$options['choices'][intval($choice)];?>" width=60 height=40>
                             [<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]
                            </li>
                             <?php
                           }
                          }
                         }
                       }
                     ?>
                     </ul>
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
                 ?> <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="200">

                 <?php
                  
                   
                 }
                
                ?>
                <div class="row border-bottom">
                <div class="col-sm-12 responsivetext">
                 <b><?=$count.". ".htmlspecialchars($questiondesc)." "?></b><span class="text-muted responsivetext">(Fill In Blanks)</span>
                 <span class="text-success  text-bold"><b>[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</b></span>
                  <span class="text-danger  text-bold"><b>[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</b></span>
             <ul>
                <?php
                
                foreach($res['inputs'] as $index=>$blank)
                {
                  if($blank==$question['blanks'][$index])
                  {
                 ?>
                  <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($blank)?>
                  [+<?="&nbsp&nbsp&nbsp".(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:0?>]
                </li>
                 <?php
                  }
                  else
                  {
                    ?>
                    <li class="ml-4  responsivetext text-danger"><?=htmlspecialchars($blank)?>
                    [<?="&nbsp&nbsp&nbsp".(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]
                  </li>
                    <?php
                  }
               
                }
               ?>
               </ul>
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
                 <b><?=$count.". ".htmlspecialchars($question['question'])." "?></b><span class="text-muted responsivetext">(Mathing Items)</span>
                 <span class="text-success  text-bold"><b>[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</b></span>
                  <span class="text-danger  text-bold"><b>[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</b></span>
                 <div class="row">
                 <table class="table" width="100%" style="border:none;margin-bottom:4px">
                  <tr>
                   <td class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="float:right;border:none">
                   <span class="p-1 pr-5 ml-4 col" style="background-color:#def">SIDE A</span>
                   <ul>
                <?php
                
                foreach($question['items'] as $index=>$item)
                {
                 if($item==null){continue;}
                 ?>
                  <li class="ml-4  responsivetext text-success" style="list-style:none"><?=$index + 1?>. <?=htmlspecialchars($item)?></li>
                 <?php
                  
               
                }
               ?>
               </ul>
              </td>
               <td class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="float:right;border:none">
               <span class="p-1 pr-5 ml-4 col" style="background-color:#def">SIDE B</span>
               <ul>
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
               <div class="row">

                 <div class="col-12 col-sm-12 ">
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
                  if(isset($res['studentmatches'][$i]) && $res['studentmatches'][$i]==$keys[$i])
                  {
                 ?>
                  <td class="text-success">
                    <?=htmlspecialchars($res['studentmatches'][$i])?>
                    [+<?="&nbsp&nbsp&nbsp".(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:0?>]
                </td>
                 <?php
                  }
                  else
                  {
                    ?>
                     <td class="text-danger">
                      <?=htmlspecialchars($res['studentmatches'][$i])?>
                      [<?="&nbsp&nbsp&nbsp".(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]
                    </td>
                    <?php
                  }
              
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
                 <b><?=$count.". ".htmlspecialchars($question['question'])." "?></b><span class="text-muted responsivetext">(Outline)</span>
                 <span class="text-success  text-bold"><b>[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</b></span>
                  <span class="text-danger  text-bold"><b>[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</b></span>
                 <ul>
                <?php
                
                foreach($res['studentalternatives'] as $index=>$alt)
                {
                  if(in_array(strtolower($alt),$question['alternatives']))
                  {
                 ?>
                  <li class="ml-4  responsivetext text-success">
                    <?=htmlspecialchars($alt)?>
                    [+<?="&nbsp&nbsp&nbsp".(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:0?>]
                  </li>
                 <?php
                  }
                  else
                  {
                    ?>
                    <li class="ml-4  responsivetext text-danger">
                      <?=htmlspecialchars($alt)?>
                      [<?="&nbsp&nbsp&nbsp".(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]
                    </li>
                    <?php
                  }
               
                }
               ?>
               </ul>
                </div>
                </div>
               <?php
                }
                $count++;
                
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




