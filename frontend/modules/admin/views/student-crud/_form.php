<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .help-block
    {
        color:red
    }
</style>
<div class="student-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reg_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'userID')->textInput() ?>

    <?= $form->field($model, 'programCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'f4_index_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'YOS')->textInput() ?>

    <?= $form->field($model, 'DOR')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
