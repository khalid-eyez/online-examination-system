<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use frontend\models\AcademicYearManager;
use common\models\Academicyear;
?>
     <!-- Navbar -->
     <nav class="main-header navbar navbar-expand  " style="background-color:white!important">
    <!-- Left navbar links -->
    
    <ul class="navbar-nav" >
      <li class="nav-item" >
        <a class="nav-link text-success" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>


    </ul>
    

    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto ">
      <!-- Fullscreen media -->
       <li class="nav-item ">
        <a class="nav-link text-success" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      <li class="nav-item dropdown mr-3">
      <?php if(Yii::$app->user->can('STUDENT')): ?>
        <a class="nav-link responsivetext text-success" data-toggle="dropdown" href="#" id="username"><span class="fas fa-user"></span>
           <i><?php echo ucwords(Yii::$app->user->identity->username) ?></i>
        </a>
      <?php endif ?>

      <?php if(Yii::$app->user->can('SYS_ADMIN') || Yii::$app->user->can('INSTRUCTOR') || Yii::$app->user->can('INSTRUCTOR & HOD') || Yii::$app->user->can('SUPER_ADMIN')): ?>
        <a class="nav-link text-success" data-toggle="dropdown" href="#" id="username"><span class="fas fa-user"></span>
          <i><?php echo " ".substr(Yii::$app->user->identity->username,0,strpos(Yii::$app->user->identity->username,"@"))?></i>
        </a>
      <?php endif ?>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right text-success">
      

            <div class="dropdown-divider"></div>

          <a href="<?= Url::to(['/portal/portal/changepassword'])  ?>" class="dropdown-item text-success">
            <i class="fas fa-lock mr-2"></i> <span class="small"> Change Password</span>
          </a>

          <div class="dropdown-divider"></div>

           <a href="#" class="dropdown-item text-success" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
            <i class="fas fa-power-off"></i><span class="small"> Logout</span>
      
          </a>
       


          <?= Html::beginForm(['/auth/logout'], 'post', ['id'=>'logout-form']) ?>
          <?= Html::endForm() ?>
         
        </div>
      </li>
     
     
    </ul>
  </nav>
  