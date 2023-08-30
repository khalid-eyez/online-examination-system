<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
<div class="card card-default shadow-lg bg-white rounded" style="font-family:'Lucida Bright'">
    <div class="card-header text-center bg-primary">
      <span><b>Request password reset</b></span>
    </div>
    <div class="card-body">
         <div class="site-request-password-reset">
            <p>Please fill out your email. A link to reset password will be sent there.</p>

            <div class="row">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                    </div>
            </div>
        </div>
    </div>
</div>

