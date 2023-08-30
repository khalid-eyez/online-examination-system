<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\password\PasswordInput;
use yii\widgets\Pjax;
use frontend\models\ClassRoomSecurity;
use yii\helpers\Url;

$this->title = 'Update Score';


?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

  <style>
    .help-block,.help-block-error
    {
      color:red;
    }
    input,
input::placeholder {
    font: 18px sans-serif;
}


</style>
<div class="container d-flex justify-content-center">
<div class="card card-default shadow bg-white rounded" style="font-family:'Lucida Bright'">
 
    <div class="card-body">
<div class="site-reset-password">
    <div class="container-fluid">
            <?php
             Pjax::begin(['id' => 'groupsform','timeout' => '300000']);
            $form = ActiveForm::begin(['id' => 'update-score',
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'options' => ['data-pjax' => true ]
            ]); ?>

                <?=$form->field($score, 'score')->textInput(['placeholder'=>'Score','class'=>'form-control'])->label(false);?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-edit"></i> Update Score', ['class' => 'btn btn-success float-right col']) ?>
                </div>

            <?php
            ActiveForm::end();
            Pjax::end();
            ?>
    </div>
    <?php
    Pjax::begin(['id' => 'loader']);
    ?>


   <div class="overlay font-weight-bold" id="loading" style="background-color:rgba(0,0,255,.2);color:#fff;display:none;position:absolute;top:35%;left:40%;height:30%;width:20%;border-radius:40px">
     <i class="fas fa-2x fa-sync-alt fa-spin text-white font-weight-bold"></i>
   </div>
   <?php

    Pjax::end();
    ?>
   </div>
</div>
</div>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){

      $('#groupsform').on('pjax:send', function() {
       $('#loading').show();
       })
      $('#groupsform').on('pjax:complete', function() {
      $('#loading').hide();
            })
        })

    JS;
    $this->registerJs($script);
