<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\password\PasswordInput;
use yii\widgets\Pjax;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
     $('#reset-password-form').submit(function (event) {
    event.preventDefault();
    grecaptcha.reset();
    grecaptcha.execute();
  });
   function onSubmit(token) {
     document.getElementById("reset-password-form").submit();
   }



 </script>
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
<div class="card card-default shadow loginbox bg-white rounded" style="font-family:'Lucida Bright'">
    <div class="card-header text-center bg-primary p-1">
      <span><b><i class="fa fa-refresh"></i> Password Reset</b></span>
    </div>
    <div class="card-body">
<div class="site-reset-password">
    <div class="container-fluid">
            <?php
             Pjax::begin(['id' => 'groupsform','timeout' => '300000']);
            $form = ActiveForm::begin(['id' => 'reset-password-form',
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'options' => ['data-pjax' => true ]
            ]); ?>

                <?=$form->field($model, 'password')->widget(
                    PasswordInput::classname(),
                    [
                    'size' => "md",
                    'options' => [
                    'placeholder' => "Password"
                    ],
                    'pluginOptions' => ['toggleMask' => false,'placeholder' => "New Password"]
                    ]
)->label(false);?>
<?=$form->field($model, 'password2')->passwordInput(['placeholder' => 'Re-type Password'])->label(false); ?>
<div class="g-recaptcha"
                   data-sitekey="6LecmnUlAAAAAE03y9kA8GfgSsdWm4MWdTf2kKtr"
                   data-size="invisible"
                   data-callback="onSubmit">
                   </div>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-refresh"></i> Reset Password', ['class' => 'btn btn-primary']) ?>
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
