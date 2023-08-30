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


<div class="site-index p-0">
    <div class="body-content p-0">
        <!-- Content Wrapper. Contains page content -->

  
        <div class="container-fluid">
           <div class="card shadow">
              <div class="card-body">
               
               <?php
                 $bankwithchapters=(new QuizManager)->chapterBankReader($chap);

                 foreach($bankwithchapters as $chapter=>$bank)
                 {
                  $module=Module::findOne($chapter);
                  ?>
                    <p style="font-size:13px;color:white;background-color:rgba(0,0,240,0.6); padding:4px;font-weight:bold">
                    <?php echo ($module!=null)?$module->moduleName:$chapter;?>
                      
                    </p>
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
                   ?>
                    <div class="row border-bottom">
                     <div class="col-sm-12 responsivetext"><?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext"><?=($question['multiple']=='on')?"(Choose Many)":"(Choose One)"?></span><span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a></div>
                     <?php
                      if($question['questionImage']!=null)
                      {
                        ?>
                        <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">
                        <?php
                      }
                     ?>
                     <div class="responsivetext col-sm-12">
                      <ul>
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
                  ?> <img class="ml-3 p-2 border-muted" src="/<?=$question['questionImage']?>" width="250" height="250">

                  <?php
                   
                    
                  }
                 
                 ?>
                 <div class="row border-bottom">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($questiondesc)." "?><span class="text-muted responsivetext">(Fill In Blanks)</span>
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
               <ul>
                 <?php
                 
                 foreach($question['blanks'] as $index=>$blank)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($blank)?></li>
                  <?php
                   
                
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
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Mathing Items)</span>
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
                
                 <table class="table" width="100%" style="border:none;margin-bottom:4px">
                  <tr>
                    <td class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="float:right;border:none">
                 <ul style="list-style-type: none;">
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
                <div class="row" style="width:100%">

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
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
                 <ul>
                 <?php
                 
                 foreach($question['alternatives'] as $index=>$alt)
                 {
                  ?>
                   <li class="ml-4  responsivetext text-success"><?=htmlspecialchars($alt)?></li>
                  <?php
                   
                
                 }
                ?>
                </ul>
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
                 <span class="float-right"><a class="float-right qdel" href="#" id=<?=$index?>><i class="fa fa-trash text-danger text-sm" data-toggle="tooltip" data-title="Delete Question"></i></span></a>
               
          
                 </div>
                 </div>
                <?php
                 }
                 //// the end of short answer questions
                 $count++;
                }
                }
              }
               ?>
             
               </div>

           </div>

        </div>
              </div>
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

$('body').on('change','.chooseq',function(){
  var name=$(this).prop('value');
 
    if(this.checked){
    var q=$(this).parent().parent().clone();
    q.find('.chooseq').remove();
    q.find('.qdel').remove();
    q.appendTo('.chosenquestions');
   $(this).parent().parent().addClass('border');
   $(this).parent().parent().addClass('m-2');
   $(this).parent().parent().addClass('p-2');
  }
  else
  {
  
    $('.chosenquestions').find($(document.getElementById(name))).remove();
    //.remove();
    $(this).parent().parent().removeClass('border');
    $(this).parent().parent().removeClass('m-2');
   $(this).parent().parent().removeClass('p-2');
  }
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




