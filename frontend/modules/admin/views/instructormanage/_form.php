<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$roles=['INSTRUCTOR'=>'INSTRUCTOR'];
?>

<div class="container pl-5 pr-5 pt-2 pb-3">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model->user->role, 'item_name')->dropdownList($roles)->label("Title") ?>
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PP')->textInput(['maxlength' => true])->label("Profile Picture (N/A)") ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'class'=>'form-group form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success btn-lg']) ?>
</div>
 

    <?php ActiveForm::end(); ?>

</div>
