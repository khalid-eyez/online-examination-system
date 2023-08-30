<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Program;

$years=['1'=>"First Year",'2'=>'Second Year','3'=>'Third Year','4'=>"Fourth Year",'5'=>'Fifthy Year'];
?>
<style>
    .help-block
    {
        color:red
    }
</style>
<div class="container pl-5 pr-5 pt-2 pb-3">

    <?php  $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reg_no')->textInput(['maxlength' => true])->label("Username") ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
    <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
