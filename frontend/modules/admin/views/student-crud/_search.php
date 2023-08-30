<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\admin\models\Studentsearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'reg_no') ?>

    <?= $form->field($model, 'userID') ?>

    <?= $form->field($model, 'programCode') ?>

    <?= $form->field($model, 'fname') ?>

    <?= $form->field($model, 'mname') ?>

    <?php // echo $form->field($model, 'lname') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'f4_index_no') ?>

    <?php // echo $form->field($model, 'YOS') ?>

    <?php // echo $form->field($model, 'DOR') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
