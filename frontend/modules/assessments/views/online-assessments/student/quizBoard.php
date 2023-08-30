<?php
use frontend\modules\assessments\models\Invigilator;
use frontend\modules\assessments\models\Vfp;

$this->title = 'Exam Taking';
?>


<div class="site-index">
    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
         <span class="d-none quiz"><?=$quiz?></span>
           <div class="card shadow" >
              <div class="card-body">
                <div  class="row border-bottom text-primary text-lg p-2 m-2 d-flex justify-content-center">
                  <div class="col-sm-3 text-info text-md p-0">
                    <marquee><?=($registered==true)?"You Are Already Registered To This Assessment, Make Sure You Submit Your Answers Otherwise Your Score Is By Default 0 (zero) !":"You Are Not Registered To This Quiz,You will be registered during submission !"?></marquee>
                  </div>
                  <div class="col-sm-2 text-center text-md">
                    Legend: <span class="text-sm"><b class="text-success">[Correct]</b> <b class="text-danger">[Incorrect]</b></span>
                  </div>
                  <div class="col-sm-5 text-center"><?=$title?> 
                  <span class="text-success text-bold pl-1"> <?=" [".$total_marks." Marks]"?></span>
                </div>
                <div class="col-sm-2  p-2 " style="position:fixed; top:7%;right:0%;z-index:20;border-radius:5px">
                <div class="container p-2 bg-black text-center">
                  <span class="float-left p-0">
                  <img src="/img/loader.gif" class="img-circle" width="17px" height="17px" />
                </span>
                <span class='timing'><?=($inititalTimer!=null)?$inititalTimer:"Time is Over!"?>
              </span><br>
              <span class='text-sm subinfo'></span>
            </div></div></div>
                <form action="submit-quiz" id="form" method="post">
               <?php
                 if(!empty($quizdata) || $quizdata!=null)
                 {
                  
                
                 $count=1;
                 
                 foreach($quizdata as $qindex=>$question)
                 {
                        /////////handling multiple choice questions
                        if($question['type']=="multiple-choice" || $question['type']=="true-false")
                        {
                   $options=$question['options'];
                   ?>
                    <div class="row border-bottom">
                     <div class="col-sm-12 "><?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext"><?=($question['multiple']=='on')?"(Choose Many)":"(Choose One)"?></span>
                     <span class="text-success text-sm text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                     <span class="text-danger text-sm text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
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
                        if($question['multiple']=="on")
                        {
                        if($options['type']=="textual")
                        {
                          foreach($options['choices'] as $index=>$choice)
                          {
                                ?>
                                 <input type="hidden" name="<?=$qindex?>[<?=$index?>]" />
                                <li style="list-style:none" class="ml-4"><input type="checkbox" class="trueq" name="<?=$qindex?>[<?=$index?>]"></input><span class="ml-1 text-muted responsivetext"><?=htmlspecialchars($choice)?></span></li>
                             <?php
                           
                              }
                          
                        }
                        else
                        {
                          foreach($options['choices'] as $index=>$choice)
                          {
                        

                              ?>
                               <input type="hidden" name="<?=$qindex?>[<?=$index?>]" />
                                <li style="list-style:none" class="ml-4">
                                <input type="checkbox"  class="trueq" name="<?=$qindex?>[<?=$index?>]">
                                <img class="img-thumbnail m-1" src="/<?=$choice?>" width=60 height=40></input>
                                </li>
                              <?php
                            }
                          }
                        }
                        else
                        {
                          if($options['type']=="textual")
                          {
                            ?>
                            <input type="hidden" name="<?=$qindex?>['nothing']" ></input>
                            <?php
                            foreach($options['choices'] as $index=>$choice)
                            {
                                  ?>
                                  
                                  <li style="list-style:none" class="ml-4">
                                  <input type="radio"  class="qradio" name="<?=$qindex?>[]" ></input>
                                  <span class="ml-1  text-muted responsivetext"><?=htmlspecialchars($choice)?></span>
                                  </li>
                               <?php
                             
                            }
                            
                          }
                          else
                          {
                            ?>
                            <input type="hidden" name="<?=$qindex?>['nothing']" ></input>
                            <?php
                      
                            foreach($options['choices'] as $index=>$choice)
                            {
                                ?>
                               
                                <li style="list-style:none" class="ml-4">
                                <input type="radio" class="qradio" name="<?=$qindex?>[]" >
                                <img class="img-thumbnail ml-1 m-1" src="/<?=$choice?>" width=60 height=40>
                                </input>
                                </li>
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
                 <div class="row border-bottom mt-2">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($questiondesc)." "?><span class="text-muted responsivetext">(Write your answers respectively in the input fields below, the correct spelling strongly matters)</span>
                  <span class="text-success text-sm text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                  <span class="text-danger text-sm text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                 
                 <div class="row m-3 p-2" style="background-color:#dde!important">
                 <?php
                 
                 foreach($question['blanks'] as $index=>$blank)
                 {
                  ?>
                  <div class="col-sm-4 col-12">
                   <input type="text" name="<?=$qindex?>[inputs][]"class="form-control" placeholder="[<?=$index +1?>]"></input>
                 </div>
                  <?php
                   
                
                 }
                ?>
                </div>
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
                     
                     <div class="row border-bottom p-2">
                     <div class="col-sm-12 responsivetext">
                      <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Choose you best match respectively from the dropdown menu below)</span>
                      <span class="text-success text-sm text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                     <span class="text-danger text-sm text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                      <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">

                        <span class="text-bold ml-4 p-1 pr-4 pl-2" style="background-color:#def">SIDE A</span>
                     
                     <?php
                     
                     foreach($question['items'] as $index=>$item)
                     {
                      if($item==null){continue;}
                      ?>
                       <li class="ml-4  responsivetext " style="list-style:none"><?=$index + 1?>. <?=htmlspecialchars($item)?></li>
                      <?php
                       
                    
                     }
                    ?>
                    
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <span class="text-bold ml-4 p-1 pr-4 pl-2" style="background-color:#def">SIDE B</span>
                     <?php
                     $matches=$question['matches'];
                     $indices=array_keys($matches);
                     shuffle($indices);
                     $shuffledmatches=[];
                     foreach($indices as $ind)
                     {
                      $shuffledmatches[$ind]=$matches[$ind];
                     }
                     foreach($shuffledmatches as $index=>$match)
                     {
                      ?>
                       <li class="ml-4  responsivetext" style="list-style:square"><?=htmlspecialchars($match)?></li>
                      <?php
                       
                    
                     }
                    ?>
                    
                    </div>
                    </div>
                    <div class="row">
    
                      <div class="col-12 col-sm-12 ">
                        
                      
                      <div class="row mb-3 pb-2" style="background-color:#dde!important">
                      <?php
                     foreach($question['items'] as $index=>$item)
                     {
                       if($item==null){continue;}
                      ?>
                       <div class="col-sm-12 col-12 col-md-6 col-lg-4 col-xl-4">
                       <span class="text-bold"><?=$index +1 ?></span>
                        <select name="<?=$qindex?>[studentmatches][]" class="form-control">
                        <option value="" selected hidden>--choose match for [<?=$index +1 ?>]--</option>
                          <?php

                            foreach($shuffledmatches as $bull=>$shuffledmatch)
                            {
                              ?>
                              <option value="<?=$bull?>"><?=htmlspecialchars($shuffledmatch)?></option>
                              <?php
                            }
                          ?>
                        </select>
                          </div>
                      <?php
                   
                     }
                    ?>
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
                 <div class="row border-bottom p-2 pb-4">
                 <div class="col-sm-12 responsivetext">
                  <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Outline your answers below, the correct spelling strongly matters)</span>
                  <span class="text-success text-sm text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                     <span class="text-danger text-sm text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                
                 <?php
                 
                 foreach($question['alternatives'] as $index=>$alt)
                 {
                  ?>
                   <input type="text" class="form-control text-primary border-left-0 border-right-0 border-top-0" name="<?=$qindex?>[studentalternatives][]" placeholder="[<?=$index+1?>]"/>
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
                         <div class="col-sm-12 responsivetext pt-2">
                          <?=$count.". ".htmlspecialchars($question['question'])." "?><span class="text-muted responsivetext">(Write your short and clear answer in the input field below!)</span>
                          <span class="text-success text-sm text-bold">[<?=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1?>]</span>
                     <span class="text-danger text-sm text-bold">[<?=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0?>]</span>
                        
                         </div>
                         <div class="col-sm-12 mt-2 mb-4">
                          <textarea class="form-control text-primary" style="background-color:rgba(245,245,255,0.5)" name="<?=$qindex?>[answer]" cols=11 rows=4 placeholder="Your Answer"></textarea>
                         </div>
                        </div>
                        <?php
                         }
                         //// the end of short answer questions
                   $count++;
                 }
                }
            
               ?>
               <input type="hidden" name="quiz" value="<?= $quiz; ?>" />
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <button type="submit" class="btn btn-default text-primary shadow p-2 mt-3 col-sm-7 float-right submitbtn"><i class="fa fa-send"></i> Submit</button>
             </form>
             <audio class="d-none messageaudio">
              <source src="/media/anxious-586.mp3"  type="audio/mpeg">
              </audio>
              <audio class="d-none overaudio">
              <source src="/media/over.mp3"  type="audio/mpeg">
              </audio> 
              <audio class="d-none successaudio">
              <source src="/media/success.mp3"  type="audio/mpeg">
              </audio> 
              <audio class="d-none failaudio">
              <source src="/media/fail.mp3"  type="audio/mpeg">
              </audio>
              <div class="overlay font-weight-bold" id="loading" style="background-color:rgba(0,0,255,.3);color:#fff;display:none;position:fixed;top:35%;left:40%;height:25%;width:20%;border-radius:40px">
              <div class="row"><span class="col-12 col-sm-12 col-md-3 text-center"><i class="fas fa-2x fa-sync-alt fa-spin text-white font-weight-bold "></i></span> <span class="col-12 col-sm-12 col-md-9 text-center responsivethumb responsivetext">Submitting...</span></div>
              </div>
               </div>

         

        </div>
        </div>
              </div>
         

</div>
 


<?php
$script = <<<JS
$(document).ready(function(){
$('body').on('change','.trueq',function(){

if(this.checked){
 
  var index=$(this).parent().parent().find('.trueq').index($(this));
 //$(this).prop('value',index);
 
  }
 
})

$('body').on('change','.qradio',function(){
 var radios=$(this).parent().parent().find('.qradio').index($(this));
 $(this).prop('value',radios);
 
  
 
})
  var allradios=$('.qradio');
  for(var i=0; i<allradios.length;i++)
  {
    var radioinput=allradios.eq(i);
    var radioindex=radioinput.parent().parent().find('.qradio').index(radioinput);
     radioinput.prop('value',radioindex); 

  }

  var allcheck=$('.trueq');
  for(var i=0; i<allcheck.length;i++)
  {
    var checkinput=allcheck.eq(i);
    var checkindex=checkinput.parent().parent().find('.trueq').index(checkinput);
    //checkinput.prop('value',checkindex); 

  }

  $("#form").on("submit", function(event){
        event.preventDefault();
       $('#loading').show();
    var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'submission request',
      'code':10,
      'warning':null
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
    var formValues= $(this).serialize();
     console.log(formValues);
 $.post("submit-quiz", formValues, function(data){
     // Display the returned data in browser
     $('#loading').hide();
     if(data.score)
     {
     var res=$.parseJSON(data.score);
     $('.successaudio').get(0).play();
     var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'Information (score='+res.score+')',
      'code':11,
      'warning':null
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
     Swal.fire(
      {
      icon:'success',
      title:"Your Score",
      text:res.score+"/"+res.totalmarks,
      footer:res.message
      }
     );
   }
   else
   {
    $('.failaudio').get(0).play();
    var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'Information',
      'code':12,
      'warning':data.failed
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
     Swal.fire(
      "Submission failed",
      data.failed,
      'error'
     );
   }
 })
 .fail(function(data) {
   $('#loading').hide();
   var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'Information',
      'code':12,
      'warning':data.responseText
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
   Swal.fire("Error",data.responseText,"error");
   return false;
   });
 
    });

    $('.submitbtn').click(function(b){

      b.preventDefault();

      Swal.fire({
  title: 'Submit Quiz?',
  text: "You won't be able to revert this! And once submitted you will no longer be able to submit",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Submit'
}).then((result) => {
  if (result.isConfirmed) {

     $('#form').submit();

  }

}
)

    })

    function updatetime()
    {
      var quiz=$('.quiz').text();
      $.post("update-quiz-timer", {quiz:quiz}, function(data){
     // Display the returned data in browser
     if(data.time!=null)
     {
       
      $('.timing').text(data.time);
      
     }
     else
       {
        $('.timing').text("Time is Over!");
        $('.img-circle').addClass('d-none');
        $('.messageaudio').get(0).play();
        $('.overaudio').get(0).play();
        var vfpdata={
        'quiz':$('.quiz').text(),
        'action':'Information',
        'code':13,
        'warning':'exceeding assessment allocated time'
        }
        $.post("record-frontend-vfps", vfpdata, function(data){});
        var time=10;
        var subinterval=setInterval(()=>{
          $('.subinfo').text("submitting in "+time+" seconds...");
          time=time-1;
        },1000);
        
        clearInterval(timeinterval);
        clearInterval(countdowninterval);
        
        var timeout=setTimeout(()=>{
         $('#form').submit();
          var vfpdata={
          'quiz':$('.quiz').text(),
          'action':'Information',
          'code':14,
          'warning':"The system has submitted the assessment automatically"
          }
          $.post("record-frontend-vfps", vfpdata, function(data){});
         clearInterval(subinterval);
         clearTimeout(timeout);
         $('.subinfo').text("submitted !");
        
        },10000);
       
       }
 
 });
    }

    var timeinterval=setInterval(updatetime, 60000);

    var countdowninterval=setInterval(function(){ 
      var time=$('.timing').text();
      var timesplit=time.split(':');
      if(parseInt(timesplit[2])>0)
      {
        timesplit[2]=parseInt(timesplit[2])-1;
      }
      else
      {
        updatetime();
      }
      var newtime=timesplit.join(":");
      $('.timing').text(newtime);
    },1000);
    var quizz=$('.quiz').text();
    var localfocus=localStorage.getItem("offcus"+quizz);
    var off_focus_no=(localfocus!="" || localfocus!=null)?localfocus:0;
document.addEventListener("visibilitychange", function() {
 
  if (document.visibilityState === 'hidden') {
    off_focus_no++;
    var quiz2=$('.quiz').text();
    localStorage.setItem("offcus"+quiz2,off_focus_no);
    if(off_focus_no<=1)
    {
    Swal.fire(
      'Attention ! Your quiz will be automatically submitted',
      'You should not got outside this window during the quiz by either opening another tab, another window or minimizing it ! if this is repeated your quiz will be automatically submitted !!',
      'info'
    );
    var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'Information',
      'code':15,
      'warning':'Attention ! Your quiz will be automatically submitted, out of focus no '+off_focus_no
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
    }
    else
    {
      $("#form").submit();
      var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'Information',
      'code':16,
      'warning':"The system might have submitted the assessment automatically, out of focus no "+off_focus_no
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
    }
  }
});
$('body').addClass("sidebar-collapse");
window.onbeforeunload=function(e){
 e.preventDefault();
 var vfpdata={
      'quiz':$('.quiz').text(),
      'action':'Information',
      'code':17,
      'warning':"graceful exit or assessment page reload"
    }
    $.post("record-frontend-vfps", vfpdata, function(data){});
 return e.returnValue = "Are you sure you want to exit?";
}


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
  <?php
   try
   {
 $vfp=new Vfp("Assessment View");
 (new Invigilator)->recordVFP($vfp,$quiz);
   }
   catch(\Exception $v)
   {
    
   }

 ?>



