<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Student */

$this->title = $model->reg_no;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="student-view">

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
