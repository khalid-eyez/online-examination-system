<?php
use yii\caching\DbDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Module;


$instructor=yii::$app->user->identity->instructor->instructorID;
$modulesdepend=new DbDependency();
$modulesdepend->sql="select count(*) from module where instructorID=$instructor";
$modules=Module::getDb()->cache(function($db) use($instructor){
  return Module::find()->where(['instructorID'=>$instructor])->all();
},0,$modulesdepend);
$modulesArray=ArrayHelper::map($modules,'moduleID','moduleName');
$modulesArray['Others']="No Module";

?>
           <!-- Content Wrapper. Contains page content -->
 <div class="container mb-3">
  <div class="modal-xl" role="document">
    <div class="modal-content shadow-sm border-none">
    <div class="modal-header bg-success pt-2 pb-2">
        <span class="modal-title" id="exampleModalLabel"><h6><i class='fa fa-edit'></i> Update Question</h6></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid questionwidget pt-2">
                 <form enctype="multipart/form-data" method="post" >
                 <div class="row ml-2 p-0 m-0">
                    <div class="col-sm-12 m-0 p-0">
                    <select class="form-control float-right mb-2" name="chapter" required>
                    <option value="" selected disabled>--select module--</option>
                    <?php

                    foreach($modulesArray as $index=>$module)
                    {
                      if(isset($buffer['chapter']) && $buffer['chapter']==$index)
                      {
                    ?>
                    <option value="<?=$index?>" selected><?=$module?></option>
                    <?php
                      }
                      else
                      {
                        ?>
                        <option value="<?=$index?>"><?=$module?></option>
                        <?php
                      }
                    }

                    ?>  
                 </select>
                  </div></div>
                  <div class="row mb-2 ml-2 p-0 m-0">
                    <div class="col-sm-6 m-0 p-0">
                 <select class="form-control float-right questiontype" name="questiontype">
                    <option value="" selected disabled>--select type--</option>
                    <option value="<?=$buffer['type']?>" name="questiontype" selected>
                    <?php
                     switch($buffer['type'])
                     {
                      case "shortanswer":
                        print "Short Answer";
                        break;
                      case "true-false":
                        print "True/False";
                        break;
                      case "multiple-choice":
                        print "Multiple choices";
                        break;
                      case "enum":
                        print "Enumeration (Listing)";
                        break;
                      case "fill-in-blanks":
                        print "Fill-in-blanks (missing words)";
                        break;
                      case "matching":
                        print "Matching Items";
                        break;
                        default:
                        print null;
                     }

                    ?>
                  </option>
                 </select>
                 </div>
                 <div class="col-sm-3 m-0 ">
                 <input type="text" class="form-control" name="score_correct" placeholder="Score if correct (default 1)" value=<?=isset($buffer['score_correct'])?$buffer['score_correct']:1?>></input>
                 </div>
                 <div class="col-sm-3 m-0 p-0">
                 <input type="text" class="form-control" name="score_incorrect" placeholder="Score if incorrect (default 0)" value=<?=isset($buffer['score_incorrect'])?$buffer['score_incorrect']:0?>></input>
                 </div>
                </div>
            
             
               <div class="row  ml-2 p-0 material-background">
               
                <div class="col-sm-1 p-0 d-flex justify-content-center m-0">
                <i class="fa fa-image fa-3x  qimage text-success" data-toggle="tooltip" data-title="Add Image To the Question"></i>
                <input type="file" name="questionImage" accept="image/*" class="d-none questionpic"></input>
                </div>
               
                <div class="col-sm-11 m-0">
              
                  <textarea class="form-control question" name="question" rows=8 cols=3 placeholder="Type Your Question Here"><?=trim($buffer['question'])?></textarea>
                  
                 </div>
             
                  </div>

                  <div class="row p-3 questionsoptions">
                   
                  <?php
                  if($buffer['type']=="true-false")
                  {
                    foreach($buffer['options']['choices'] as $index=>$choice)
                    {
                  ?>
                  <div class="card col-sm-3 material-background firstopt">
                  <div class="card-header p-2 text-success">
                    <div class="row p-0">
                      <div class="col-sm-12 p-0">
                    <i data-toggle="tooltip" data-title="Remove Option" class="fa fa-trash-alt fa-1x text-success btn btn-sm btn-default float-left mr-1 p-0 remove d-none" ></i>
                   <i data-toggle="tooltip" data-title="Turn To An Image Option" class="fa fa-image text-success float-left btn btn-default btn-sm p-0 img d-none" style="font-size:20px"></i>
                   <input type="checkbox" name="" class="m-0 p-0 float-right trueq" <?=array_key_exists($index,$buffer["options"]["true-choices"])?"checked='checked'":""?>></input>
                   <input type="file" name="optionImage[]"  accept="image/*" class="d-none thefile"></input>
                   </div>
                  </div>
                </div>
                  <div class="card-body p-0 pb-1">
                  <textarea class="form-control" name="options[]" placeholder="Type Answer Option"><?=$choice?></textarea>
                 
                  </div>
                   </div>
                   <?php
                    }
                  }
                  else if($buffer['type']=="multiple-choice")
                  {
                    foreach($buffer['options']['choices'] as $index=>$choice)
                    {
                  ?>
                  <div class="card col-sm-3 material-background firstopt">
                  <div class="card-header p-2 text-success">
                    <div class="row p-0">
                      <div class="col-sm-12 p-0">
                    <i data-toggle="tooltip" data-title="Remove Option" class="fa fa-trash-alt fa-1x text-success btn btn-sm btn-default float-left mr-1 p-0 remove " ></i>
                   <i data-toggle="tooltip" data-title="Turn To An Image Option" class="fa fa-image text-success float-left btn btn-default btn-sm p-0 img " style="font-size:20px"></i>
                   <input type="checkbox" name="" class="m-0 p-0 float-right trueq" <?=array_key_exists($index,$buffer["options"]["true-choices"])?"checked='checked'":""?>></input>
                   <input type="file" name="optionImage[]"  accept="image/*" class="d-none thefile"></input>
                   </div>
                  </div>
                </div>
                  <div class="card-body p-0 pb-1">
                  <textarea class="form-control" name="options[]" placeholder="Type Answer Option"><?=$choice?></textarea>
                 
                  </div>
                   </div>
                   <?php
                    }
                    ?>
                    <span class="row btn btn-sm shadow btn-default addmore" data-toggle="tooltip" data-title="Add more options" style="position:absolute; right:1%; top:60%"><i class="fa fa-plus-circle fa-1x"></i></span>
                    <?php
                  }
                  else if($buffer['type']=="fill-in-blanks")
                  {
                    ?>
                    <span class='font-weight-bold bg-primary p-2 col mb-2'>What Should Be filled in</span>
                    <?php
                    foreach($buffer['blanks'] as $index=>$blank)
                    {
                      ?>
                      <input type='text' class='form-control mb-1 blank' name='blanks[]' value="<?=$blank?>" placeholder='[blank <?=$index +1?>]' required/>
                      <?php
                    }
                    ?>
                    <span class="row btn btn-sm shadow btn-default addblank" blanknum=<?=count($buffer['blanks'])?> data-toggle="tooltip" data-title="Add more options" style="position:absolute; right:1%; top:60%"><i class="fa fa-plus-circle fa-1x"></i></span>
                    <span class="row btn btn-sm shadow btn-default reset" data-toggle="tooltip" data-title="Reset everything" style="position:absolute; right:1%; top:67%"><i class="fa fa-refresh fa-1x"></i></span>
                    <?php
                  }
                  else if($buffer['type']=="matching")
                  {
                    ?>
                      <span class='font-weight-bold bg-primary p-2 mb-2'>Matches: <i class='text-sm'>This is a one to one matching, matching is done from side A to side B, if an item does not have it's best match, leave it blank (items will be shuffled on the student's side)</i></span>
                      <div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 text-center'> SIDE A
                      </div>
                      <div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 text-center'> SIDE B
                      </div>
                      <div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 sidea'>
                        <?php
                       
                          foreach($buffer['items'] as $index=>$item)
                          {
                            ?>
                            
                            <input type='text' class='form-control' value="<?=$item?>" placeholder='item <?=$index +1?>' name='items[]' />
                            <?php
                            
                          }
                       ?>
                      
                      </div>
                      <div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 sideb'>
                  
                      <?php
                       $count=1;
                       foreach($buffer['matches'] as $index=>$match)
                       {
                         ?>
                         
                         <input type='text' class='form-control' value="<?=$match?>" placeholder='match for item <?=$count?>' name='matches[<?=$index?>]' char="<?=$index?>"/>
                         <?php
                         $count ++;
                       }
                    ?>
                      </div>
                    <?php
                  ?>
                 
                  <span class="row btn btn-sm shadow btn-default resetmatches" data-toggle="tooltip" data-title="Reset everything" style="position:absolute; right:1%; top:67%"><i class="fa fa-refresh fa-1x"></i></span>
                  <span class="row btn btn-sm shadow btn-default addmatch" matchnum=<?=count($buffer['matches'])?> data-toggle="tooltip" data-title="Add more options" style="position:absolute; right:1%; top:60%"><i class="fa fa-plus-circle fa-1x"></i></span>
                  <?php
                 
                  }
                  else if($buffer['type']=="enum")
                  {
                  ?>
                    <span class='font-weight-bold bg-primary p-2 col mb-2'>Expected Items <i class='text-sm'>(if there are alternatives, add them too)</i></span>
                  <?php
                    foreach($buffer['alternatives'] as $index=>$alternative)
                    {
                      ?>
                         <input type='text' class='form-control mb-1' value="<?=$alternative?>" name='alternatives[]' placeholder='Add Item' required/>
                        
                      <?php
                    }
                    ?>
                    <span class="row btn btn-sm shadow btn-default additem" data-toggle="tooltip" data-title="Add more options" style="position:absolute; right:1%; top:60%"><i class="fa fa-plus-circle fa-1x"></i></span>
                    <span class="row btn btn-sm shadow btn-default resetenum" data-toggle="tooltip" data-title="Reset everything" style="position:absolute; right:1%; top:67%"><i class="fa fa-refresh fa-1x"></i></span>
                    <?php
                  }
                  ?>
                    
               
                    
                

                  
                    </div>
   
                    
                  <div class="row p-0 ">
                    <div class="col-sm-6 form-check ">
                  <span class="form-group float-left ml-3 answerdec"><input type="checkbox" name="answerdecision" class="form-check-input" id="moreq"></input><label for="moreq">Accept More Than One Correct Answer</label></span>
                 
                  </div>
                  <div class="col-sm-6 ">
                  <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                  <span class="float-right"><button type="submit"  class="btn btn-success btn-md shadow"><i class="fa fa-save"></i> Save</button></span>
                  </div>
                  </div>
                  </div>

</form>
               </div>
               
               </div>

        
     
    </div>
    </div>
     
     </div>


<?php
$script = <<<JS
$(document).ready(function(){
  var optarea=$('.questionwidget').find('.questionsoptions');
  var addmore=$('.addmore');
  var char="A";
  var count=0;
  var blanknum=0;
  var reset='<span class="row btn btn-sm shadow btn-default reset" data-toggle="tooltip" data-title="Reset everything" style="position:absolute; right:1%; top:67%"><i class="fa fa-refresh fa-1x"></i></span>';
  var initialoption='<div class="card col-sm-3">';
  initialoption+='<div class="card-header p-2 text-success">';
  initialoption+='<div class="row p-0">';
  initialoption+='<div class="col-sm-12 p-0">';
  initialoption+='<i data-toggle="tooltip" data-title="Remove Option" class="fa fa-trash-alt fa-1x text-success btn btn-sm btn-default float-left mr-1 p-0 remove" ></i>';
  initialoption+='<i data-toggle="tooltip" data-title="Turn To An Image Option" class="fa fa-image text-success float-left btn btn-default btn-sm p-0 img" style="font-size:20px"></i>';
  initialoption+=' <input type="checkbox" name="" class="m-0 p-0 float-right trueq"></input>';
  initialoption+='<input type="file" name="questionfile" accept="image/*" class="d-none thefile"></input>';
  initialoption+='</div>';
  initialoption+='</div>';
  initialoption+='</div>';
  initialoption+='<div class="card-body p-0 pb-1">';
  initialoption+='<textarea class="form-control" name="options[]" placeholder="Type Answer Option"></textarea>';
  initialoption+='</div>';
  initialoption+=' </div>';
$('.questiontype').on('change',function(){



  
  if($('.questiontype').val()=="true-false")
  {
    blanknum=0;
    count=0;
    optarea.html("");
    var optiontrue= $(initialoption);
    optiontrue.find('textarea').val('True');
    optiontrue.find(".img").addClass('d-none');
    optiontrue.find(".remove").addClass('d-none');
    var optionfalse= $(initialoption);
    optionfalse.find('textarea').val('False');
    optionfalse.find(".img").addClass('d-none');
    optionfalse.find(".remove").addClass('d-none');
    optarea.append(optiontrue).append(optionfalse);
    $('.addmore').remove();
    $('.answerdec').addClass("d-none");
    $('.question').val("");

  }
  else if($('.questiontype').val()=="multiple-choice")
  {
    blanknum=0;
    count=0;
    optarea.html("").append(initialoption).append(addmore);
    addmore.addClass("addmore");
    addmore.removeClass("addblank");
    addmore.removeClass("d-none");
    addmore.removeClass("additem");
    addmore.removeClass("addmatch");
    $('.answerdec').removeClass("d-none");
    $('.question').val("");
  }
  else if($('.questiontype').val()=="fill-in-blanks")
  {
    count=0;
    blanknum=0;
    addmore.removeClass("addmore");
    addmore.removeClass("additem");
    addmore.removeClass("d-none");
    addmore.removeClass("addmatch");
    addmore.remove();
    addmore.addClass("addblank");
    addmore.attr("data-title","Add Blank");
    optarea.html("").append(addmore);
    optarea.append(reset);
    optarea.append("<span class='font-weight-bold bg-primary p-2 col mb-2'>What Should Be filled in</span>");
    addmore.tooltip();
    $('.reset').tooltip();
    $('.answerdec').addClass('d-none');
    $('.question').val("");
    
  }

  else if($('.questiontype').val()=="enum")
  {
    blanknum=0;
    count=0;
    var additem=addmore;
    additem.removeClass("addmore");
    additem.removeClass("d-none");
    additem.removeClass("addblank");
    additem.removeClass("addmatch");
    additem.remove();
    additem.addClass("additem");
    additem.attr("data-title","Add Items");
    
    optarea.html("").append(additem);
    var resetenum=$(reset).removeClass("reset");
    resetenum.addClass("resetenum");
    optarea.append(resetenum);
    optarea.append("<span class='font-weight-bold bg-primary p-2 col mb-2'>Expected Items <i class='text-sm'>(if there are alternatives, add them too)</i></span>");
    additem.tooltip();
    $('.reset').tooltip();
    $('.answerdec').addClass('d-none');
    $('.question').val("");

  }


  else if($('.questiontype').val()=="matching")
  {
    blanknum=0;
    count=0;
    $('.question').val("");
    var additem=addmore;
    additem.removeClass("addmore");
    additem.removeClass("d-none");
    additem.removeClass("addblank");
    additem.removeClass("additem");
    additem.remove();
    additem.addClass("addmatch");
    additem.attr("data-title","Add Matches");
    
    optarea.html("").append(additem);
    var resetmatches=$(reset).removeClass("reset");
    resetmatches.addClass("resetmatches");
    optarea.append(resetmatches);
    optarea.append("<span class='font-weight-bold bg-primary p-2 mb-2'>Matches: <i class='text-sm'>This is a one to one matching, matching is done from side A to side B, if an item does not have it's best match, leave it blank (items will be shuffled on the student's side)</i></span>");
    var sides="<div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 text-center'> SIDE A";
    sides+="</div><div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 text-center'> SIDE B";
    sides+="</div>";

    optarea.append(sides);
    additem.tooltip();
    $('.resetmatches').tooltip();
    $('.answerdec').addClass('d-none');
  }
  else
  {
    count=0;
    blanknum=0;
    optarea.html("");
    $('.question').val("");
    addmore.addClass("d-none");
    $('.answerdec').addClass('d-none');
  }

})

count=$('.addmatch').attr('matchnum');
$('body').on('click','.addmatch',function(){

count++;
var match="<div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 '>";
    match+="<input type='text' class='form-control item' placeholder='item "+count+"' name='items[]' />";
    match+="</div><div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0'>";
    match+="<input type='text' class='form-control match' placeholder='match for item "+count+"' name='matches["+char+"]' />";
    match+="</div>";

    optarea.append(match);
    char=nextChar(char);


});

$('.addmore').parent().parent().on('click','.addmore',function(){
  $('.questionsoptions').append(initialoption);
});
blanknum=$('.addblank').attr('blanknum');
$('body').on('click','.addblank',function(){

 blanknum++;
 var placeholder="[#### blank"+blanknum+"] ";
 var question=$('.question');
 var value=question.val();
 question.val(value+placeholder);

 var blankinput="<input type='text' class='form-control mb-1' name='blanks[]' placeholder='[blank "+blanknum+"]' required/>";
 optarea.append(blankinput);
})
$('body').on('click','.additem',function(){
 var blankinput="<input type='text' class='form-control mb-1' name='alternatives[]' placeholder='Add Item' required/>";
 optarea.append(blankinput);
})
$('body').on('click','.reset',function(){
  addmore=$('.addblank');
  blanknum=0;
  optarea.html("");
  $('.question').val("");
  addmore.addClass("addblank");
  optarea.append(addmore);
  optarea.append("<span class='font-weight-bold bg-primary p-2 col mb-2'>What Should Be filled in</span>");
  optarea.append(reset);
  $('.tooltip').tooltip('hide');
})

$('body').on('click','.resetenum',function(){
  addmore=$('.additem');
  optarea.html("");
  optarea.append(addmore);
  var resetenum=$(reset).removeClass("reset");
  resetenum.addClass("resetenum");
  optarea.append(resetenum);
  optarea.append("<span class='font-weight-bold bg-primary p-2 col mb-2'>Expected Items <i class='text-sm'>(if there are alternatives, add them too)</i></span>");
  $('.tooltip').tooltip('hide');
})

$('body').on('click','.resetmatches',function(){
  addmore=$('.addmatch');
  optarea.html("");
 
  var resetmatches=$(reset).removeClass("reset");
    resetmatches.addClass("resetmatches");
    optarea.append(resetmatches);
    optarea.append(addmore);
    optarea.append("<span class='font-weight-bold bg-primary p-2 mb-2'>Matching Items: <i class='text-sm'>This is a one to one matching, matching is done from side A to side B, if an item does not have it's best match, leave it blank (items will be shuffled on the student's side)</i></span>");
    var sides="<div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 text-center'> SIDE A";
    sides+="</div><div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0 text-center'> SIDE B";
    sides+="</div>";

    optarea.append(sides);
    $('.tooltip').tooltip('hide');
    count=0;
    char="A";
    
 
})

$('body').on('click','.remove',function(){

  $(this).parent().parent().parent().parent().remove();

 
})

$('body').on('click','.img',function(){
  $(this).parent().find('.thefile').trigger('click');
})


$('body').on('click','.qimage',function(){
  $(this).parent().find('.questionpic').trigger('click');
})

$('body').on('change','.questionpic',function(){
  if($(this).prop('files')[0].length!=0){
   $('.qimage').addClass('text-success');
  }
})

$('body').on('change','.thefile',function(){
  if($(this).prop('files')[0].length!=0){
    $(this).parent().find('.img').addClass('text-success');
    $(this).parent().find('.remove').addClass('text-success');
    $(this).parent().parent().parent().parent().addClass('shadow');
    $(this).parent().parent().parent().parent().find('textarea').val('Image').prop('disabled',true);
  }
})

$('body').on('change','.trueq',function(){

 if(this.checked){
  
  $(this).prop('name',$('.trueq').index($(this)));
  
   }
  
})

////////////////// charter increment ///////////////////

function nextChar(c) {
        var u = c.toUpperCase();
        if (same(u,'Z')){
            var txt = '';
            var i = u.length;
            while (i--) {
                txt += 'A';
            }
            return (txt+'A');
        } else {
            var p = "";
            var q = "";
            if(u.length > 1){
                p = u.substring(0, u.length - 1);
                q = String.fromCharCode(p.slice(-1).charCodeAt(0));
            }
            var l = u.slice(-1).charCodeAt(0);
            var z = nextLetter(l);
            if(z==='A'){
                return p.slice(0,-1) + nextLetter(q.slice(-1).charCodeAt(0)) + z;
            } else {
                return p + z;
            }
        }
    }
    
    function nextLetter(l){
        if(l<90){
            return String.fromCharCode(l + 1);
        }
        else{
            return 'A';
        }
    }
    
    function same(str,char){
        var i = str.length;
        while (i--) {
            if (str[i]!==char){
                return false;
            }
        }
        return true;
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




