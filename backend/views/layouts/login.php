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
    $this->registerCsrfMetaTags();
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/logo.png']);
     ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition login-page ">
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
<div class="login-box text-center">
  <!-- /.login-logo -->
<?= $content ?>
</div>
<?= $this->render('/includes/loginfooter') ?>
<!-- /.login-box -->
<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>
