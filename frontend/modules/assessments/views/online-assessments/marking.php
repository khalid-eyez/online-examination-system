<?php 
use frontend\modules\assessments\models\QuizManager;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Security;
use common\helpers\Custom;
use common\models\QMarks;
use common\models\Instructor;
use yii\helpers\ArrayHelper;
use frontend\models\ClassRoomSecurity;
$this->title = 'Quiz Marking';

$submits=$quiz->studentQuizzes;

?>
<body>
  <div class="container-fluid">
<div class="row d-none"><div class="col-md-6" id="coursecode" >XXX</div><div class="col-md-6" id="assidt"><?=$quiz->quizID?></div></div>
<div class="row pt-2 pb-2 shadow-sm">
  <div class="col-md-3 col-ms-3 "  >
    <div class="row ">
    <span class="text-md col-md-4 shadow-sm pt-1 pb-1">Marked:</span><span id="markedperc" class="text-primary col-md-3 shadow-sm"></span><span class="col-md-5 shadow-sm">of &nbsp&nbsp&nbsp&nbsp<?=count($submits)?> </span>
</div>
    <div class="row pt-2 pb-2 shadow bg-light" id="markcontrol2" style="position:fixed;z-index:20;left:50%">
    <div class="col-md-12 col-ms-12 d-flex  justify-content-center" >
      <span class="btn btn-sm btn-default mr-5" data-toggle="tooltip" data-title="Skip Back" id="skipback"><i class="fa fa-arrow-circle-left fa-2x text-success"></i>
    </span>
    <span class="btn btn-lg btn-default shadow text-success" id="savemove"data-toggle="tooltip" data-title="Save And Move"><i class="fa fa-save ">Save</i>
  </span>
  <span class="btn btn-sm btn-default ml-5" id="skipnext" data-toggle="tooltip" data-title="Skip Next"><i class="fa fa-arrow-circle-right fa-2x text-success"></i>
</span>
</div>
</div>
</div>
<div class="col-md-6 col-ms-6 d-flex justify-content-center" >
  <span class=" text-primary text-center" id="currentass"></span>
</div>
<div class="col-md-3 col-ms-3 ">
  <div class="row"><div class="col-md-4"></div><a href="<?=Url::to(['/assessments/online-assessments/download-markable-submits','assessment'=>$_GET['quiz']])?>" class="col-md-2"><i class="fas fa-download text-success" data-toggle="tooltip" data-title="Download all submits"></i></a>
</div>
</div>
</div>

<div class="row shadow">
  <?php
if($submits!=null)
{
  ?>
<div class="col-md-2  studenttable" style="max-height:400px;overflow:auto">  
<table class="table d-flex mytable" style="font-size:10px;cursor:pointer">
<tr class="d-flex"><th>s/no</th><th>reg #</th></tr>

<?php 

for($sub=0;$sub<count($submits);$sub++)
{
  if($submits[$sub]->isFullyMarked() || $submits[$sub]->status!="submitted"){continue;}
  if($submits[$sub]->score!=null || $submits[$sub]->score!="")
  {
?>
<tr class="d-flex text-primary"><td id="<?=$submits[$sub]->SQ_ID;?>" quiz="<?=$submits[$sub]->quizID;?>"><?=$sub+1?><td id="<?=$submits[$sub]->file;?>"><?php print $submits[$sub]->reg_no;?></td></tr>
<?php
  }
  else
  {
    ?>

<tr class="d-flex"><td id="<?=$submits[$sub]->SQ_ID;?>" quiz="<?=$submits[$sub]->quizID;?>"><?=$sub+1?><td id="<?=$submits[$sub]->file;?>"><?php print $submits[$sub]->reg_no;?></td></tr>
    <?php
  }
}
?>

</table>
</div>
  <div class="col-md-8 shadow d-flex justify-content-center">
    <span class="d-none savespin bg-primary overlay p-4 opacity-75 rounded-pill" style="position:absolute;z-index:2;bottom:50%;opacity:.7"><i class="fas fa-sync-alt fa-spin fa-2x " ></i>Saving...</span>
    <iframe src="" title="Displays Assessment Files" style="position: relative; height: 100%; width: 100%;border:none" frameborder="0" height="426" id="fileobj"  type="application/pdf">
    file not found or could not be read

    </iframe>
    <!-- <div id="viewpdf"></div> -->

  </div>

<div class="col-md-2 shadow ">
<div class="container markables">
<?php
$total=0;
$count=0;
if($quiz->attempt_mode=="massive")
{
foreach($markables as $qid=>$question)
{
  $correctscore=(isset($question['score_correct']) && $question['score_correct']!=null)?$question['score_correct']:1;
  $incorrectscore=(isset($question['score_incorrect']) && $question['score_incorrect']!=null)?$question['score_incorrect']:0;
  $total+=$correctscore;
  
  ?>
  <div id="marks" class="row qmarking">
<div id="mrow" class="col-md-12" style="margin-top:7px">
<div class="form-group">
<input type="text" class="form-control score text-center" id="" placeholder="Q<?=++$count?> [<?=$correctscore?>][<?=$incorrectscore?>]" value="">
</input><input type="text" class="form-control maxscore text-bold text-center" value=<?=$question['score_correct']?> readonly></input></div>
</div>
</div>
  <?php
}
}

?>
</div>
<!--question marking-->
<div class="row">
<div class="col-md-12">
<div class="row m-1 text-sm" style="background-color:#def"><div class="col-md-12 text-primary">Aggregate</div></div>
<div class="row">
<div class="col-md-6 form-group score_mark">

   <input id="scoremark" type="text" value="" name="grad" class="form-control" style="color:blue"></input>
</div>
<div class="col-md-6 form-group">
<input id="tot" type="text"  name="tat" value="<?=$total?>" class="form-control text-center text-bold"  readonly></input>
</div>
</div>
<!--end of question marking-->
</div>
</div>


</div>

<?php
}
else
{
  print '<div class="container-fluid text-primary text-center p-5">No any submits</div>';
}
?>

</div>
</div>
<?php
$this->registerJsFile(
  '@web/js/quizmarking.js',
  ['depends' => 'yii\web\JqueryAsset']

);

?>