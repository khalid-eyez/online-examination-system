<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = $model->moduleID;
\yii\web\YiiAsset::register($this);
?>
<div class="module-view">



    <p>
        <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->moduleID], ['class' => 'btn btn-success float-right m-2']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->moduleID], [
            'class' => 'btn btn-danger float-right m-2',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'moduleName',
        ],
    ]) ?>

</div>
