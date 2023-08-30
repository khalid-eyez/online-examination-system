<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Department;
use yii\helpers\ArrayHelper;
use common\models\College;

/* @var $this yii\web\View */
/* @var $model common\models\Instructor */
/* @var $form yii\widgets\ActiveForm */


  $colleges = ArrayHelper::map(College::find()->all(), 'collegeID', 'college_name');
?>

<div class="container pl-5 pr-5 pt-2 pb-3">

    <?php $form = ActiveForm::begin(); ?>

  

    <?= $form->field($model, 'collegeID')->dropdownList($colleges)->label("College") ?>
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'class'=>'form-group form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-default btn-lg ']) ?>
</div>
 

    <?php ActiveForm::end(); ?>

</div>
