<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use frontend\models\AcademicYearManager;
use common\models\Academicyear;
?>
     <!-- Navbar -->
     <nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
    <!-- Left navbar links -->
    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>


    </ul>
    
   
    <ul class="navbar-nav ml-auto">
    <?php if(Yii::$app->user->can('INSTRUCTOR & HOD') || Yii::$app->user->can('INSTRUCTOR') ){ ?>
   <li class="nav-item d-none d-md-block">
    <?php
       $yearmodel=new AcademicYearManager;
       $yearmodel->yearid=(yii::$app->session->get('currentAcademicYear'))->yearID; 

        //preparing academic years
    $academicyears=Academicyear::find()->all();
    $mappedyears=ArrayHelper::map($academicyears,'yearID','title');
    
    $form = ActiveForm::begin(['method'=>'post','options'=>['class'=>'form-inline form-horizontal'], 'action'=>['/portal/instructor/switch-academicyear']]);?>

        <div class="row"><div class="col-md-8 col-sm-8  nav-link" style="padding-right:1;padding-left:0"><div class="form-group">
        <?= $form->field($yearmodel, 'yearid')->dropDownList($mappedyears,['class'=>'btn-default btn-sm rounded-pill'])->label('Academic Year',['class'=>'text-md d-none d-md-block'])?>
</div>
</div><div class="col-md-4 col-sm-4 nav-link" style="padding-right:0;padding-left:1"><div class="form-group" >
       <?=Html::submitButton('<i class="fa fa-refresh"></i> Switch',['class'=>'btn btn-sm  btn-default rounded-pill '])?>
</div></div>

        <?php ActiveForm::end()?>
        <?php
}
else if(Yii::$app->user->can('STUDENT'))
{
  ?>
  <div class="col-md-12 col-sm-12 text-sm nav-link d-none d-md-block">
  <?=Html::Button('<i class="fa fa-clock-o"></i> '.(yii::$app->session->get('currentAcademicYear'))->title,['class'=>'btn btn-lg p-1  btn-default rounded-pill '])?>
  <?=yii::$app->user->identity->student->programCode." ".yii::$app->user->identity->student->YOS?>
</div>
  <?php
}
else
{
?>
  <div class="col-md-12 col-sm-12 text-sm nav-link d-none d-md-block">
  <?=Html::Button('<i class="fa fa-clock-o"></i> '.(yii::$app->session->get('currentAcademicYear'))->title,['class'=>'btn btn-lg p-1  btn-default rounded-pill '])?>
</div>
<?php
}
?>
</li>

</ul>
    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
    <!--   <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->
      <!-- Fullscreen media -->
       <li class="nav-item ">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>

        
      <!-- </li  class="navbar-nav">
    <?php if(Yii::$app->user->can('STUDENT')): ?>
      <a class="nav-link"  href="<?= Url::to(['student/student_groups'])  ?> ">
          <i class="nav-item fa fa-users"></i> 
        </a>
      <?php endif ?>
    </li> -->
      <!-- Notifications Dropdown Menu -->
     
      <li class="nav-item dropdown mr-3">
      <?php if(Yii::$app->user->can('STUDENT')): ?>
        <a class="nav-link responsivetext" data-toggle="dropdown" href="#" id="username"><span class="fas fa-user"></span>
           <i><?php echo ucwords(Yii::$app->user->identity->student->reg_no) ?></i>
        </a>
      <?php endif ?>

      <?php if(Yii::$app->user->can('SYS_ADMIN') || Yii::$app->user->can('INSTRUCTOR') || Yii::$app->user->can('INSTRUCTOR & HOD') || Yii::$app->user->can('SUPER_ADMIN')): ?>
        <a class="nav-link" data-toggle="dropdown" href="#" id="username"><span class="fas fa-user"></span>
          <i><?php echo " ".substr(Yii::$app->user->identity->username,0,strpos(Yii::$app->user->identity->username,"@"))?></i>
        </a>
      <?php endif ?>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <?php if(Yii::$app->user->can('STUDENT')): ?>
                <a href="<?= Url::to(['/portal/studentprofile/view'])  ?>" class="dropdown-item">
                    <i class="fa fa-user-circle mr-2"></i> <span class="small">My Profile</span>
                </a>
            <?php endif ?>

            <div class="dropdown-divider"></div>

          <a href="<?= Url::to(['/portal/portal/changepassword'])  ?>" class="dropdown-item">
            <i class="fas fa-lock mr-2"></i> <span class="small"> Change Password</span>
          </a>

          <div class="dropdown-divider"></div>

           <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
            <i class="fas fa-power-off"></i><span class="small"> Logout</span>
      
          </a>
       


          <?= Html::beginForm(['/auth/logout'], 'post', ['id'=>'logout-form']) ?>
          <?= Html::endForm() ?>
         
        </div>
      </li>
     
     
    </ul>
  </nav>
  