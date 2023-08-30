<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
//use Yii;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    $this->registerCsrfMetaTags() ;
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/logo.png']);
    ?>
    <title><?= Html::encode($this->title) ?></title>
    <style>

@media only screen and (max-width: 600px) {
  .loginbox
  {
    width:100%;
    margin-top:13%!important;
    
  }
  
}

@media only screen and (min-width: 600px) {
  .loginbox
  {
    width:70%;
    margin-top:25%!important;
    
  }
  .loginfooter
  {
    left:29%!important;
  }
}
@media only screen and (min-width: 768px) {
  .loginbox
  {
    width:60%;
    margin-top:20%!important;
    
  }
  .loginfooter
  {
    left:29%!important;
  }
}
@media only screen and (min-width: 992px) {
  .loginbox
  {
    width:40%;
    margin-top:14%!important;
    
  }
  .loginfooter
  {
    left:31%!important;
  }
}
@media only screen and (min-width: 1200px) {

  .loginbox
  {
    width:40%;
    margin-top:14%!important;
    
  }
  .loginfooter
  {
    left:40%!important;
  }

} 
    
    </style>
    <?php $this->head() ?>
</head>
<body class="" style="background-color:#edeff1">

  <!-- /.login-logo -->
<?=$this->render("/includes/loginheader")?>
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo Yii::getAlias('@web/img/logo.png'); ?>" alt="LOGO" height="60" width="60">
  </div> 
  <div class="container ">
     <div class="row mt-3 show-sm">
      <?php if(Yii::$app->session->hasFlash('success')): ?>

          <div class="col-md-12 text-center">
            <div class="alert alert-success alert-dismissible">
              <button class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
              <strong><?= Yii::$app->session->getFlash('success') ?></strong>
            </div>
          </div>
      
      <?php endif ?>
       <?php if(Yii::$app->session->hasFlash('error')): ?>
          <div class="col-md-12 text-center">
            <div class="alert alert-danger alert-dismissible">
              <button class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
              <strong><?= Yii::$app->session->getFlash('error') ?></strong>
            </div>
          </div>
        
      <?php endif ?>
       </div>
       </div>
<?= $content ?>
<?= $this->render('/includes/loginfooter') ?>
<!-- /.login-box -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
