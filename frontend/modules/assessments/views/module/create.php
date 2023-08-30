<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = 'Create Module';

?>
<div class="module-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
