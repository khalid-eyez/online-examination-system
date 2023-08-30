<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InsructorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instructor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'instructorID') ?>

    <?= $form->field($model, 'userID') ?>

    <?= $form->field($model, 'departmentID') ?>

    <?= $form->field($model, 'full_name') ?>

    <?= $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'PP') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
