<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Student */

$this->title = $model->reg_no;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['student-list']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="student-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->reg_no], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset Password', ['reset', 'id' => $model->userID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->reg_no], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'reg_no',
            'userID',
            'programCode',
            'fname',
            'mname',
            'lname',
            'email:email',
            'gender',
            'f4_index_no',
            'YOS',
            'DOR',
            'phone',
            'status',
        ],
    ]) ?>

</div>
