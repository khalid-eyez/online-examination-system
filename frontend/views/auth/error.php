<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1 class="text-warning"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger" style="opacity:0.7">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p class="text-center">
        
        <i class="far fa-frown fa-8x text-warning"></i>  

  </p> 
  

</div>
