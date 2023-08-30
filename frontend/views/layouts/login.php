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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php 
    $this->registerCsrfMetaTags();
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
  <?=$this->render("/includes/loginheader")?>
  <div class="preloader flex-column justify-content-center align-items-center">
    <b class="animation__shake text-success text-lg border border-success p-4" style="border-radius:50%">OES</b>
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
<div class="text-center">
  <!-- /.login-logo -->
<?= $content ?>
</div>
<!--$this->render('/includes/loginfooter')-->
<!-- /.login-box -->
<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>
