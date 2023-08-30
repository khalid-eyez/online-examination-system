<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = 'Update Module: ' . $model->moduleID;
?>
<div class="module-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
